<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/email_functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Honeypot spam protection
if (!validateHoneypot()) {
    echo json_encode(['success' => false, 'message' => 'Spam detected']);
    exit;
}

// Rate limiting
$clientIP = getUserIP();
if (!checkRateLimit($clientIP, 5, 300)) { // 5 attempts per 5 minutes
    echo json_encode(['success' => false, 'message' => 'Too many requests. Please try again later.']);
    exit;
}

// Validate required fields
$required_fields = ['full_name', 'phone'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
    }
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Sanitize and validate inputs
$therapist_id = !empty($_POST['therapist_id']) ? (int)$_POST['therapist_id'] : null;
$full_name = sanitizeInput($_POST['full_name']);
$email = sanitizeInput($_POST['email']);
$phone = sanitizeInput($_POST['phone']);
$message = sanitizeInput($_POST['message'] ?? '');
$preferred_date = sanitizeInput($_POST['preferred_date'] ?? '');

// Validate email (only if provided)
if (!empty($email) && !validateEmail($email)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Validate phone
if (!validatePhone($phone)) {
    echo json_encode(['success' => false, 'message' => 'Invalid phone number format']);
    exit;
}

// Add preferred date to message if provided
if ($preferred_date) {
    $message .= "\n\nPreferred Date: " . $preferred_date;
}

try {
    // Create inquiry
    $inquiryData = [
        'therapist_id' => $therapist_id,
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone,
        'message' => $message
    ];
    
    $result = createInquiry($inquiryData);
    
    if ($result['success']) {
        // Get therapist name if available
        $therapistName = null;
        if ($therapist_id) {
            $therapist = getTherapistById($therapist_id);
            $therapistName = $therapist ? $therapist['name'] : null;
        }
        
        // Prepare email data
        $emailData = array_merge($inquiryData, [
            'inquiry_id' => $result['lead_id'],
            'therapist_name' => $therapistName
        ]);
        
        // Send notification email
        $emailSent = sendInquiryNotification($emailData);
        
        // Log email attempt for debugging
        error_log("Inquiry notification email attempt - Inquiry ID: " . $result['lead_id'] . ", Email sent: " . ($emailSent ? 'Yes' : 'No'));
        
        echo json_encode([
            'success' => true, 
            'message' => 'Your inquiry has been sent successfully! We will contact you soon.' . ($emailSent ? ' Notification sent.' : ''),
            'inquiry_id' => $result['lead_id']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred while processing your inquiry']);
}
?>