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

$sessionId = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;
$name = sanitizeInput($_POST['name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$phone = sanitizeInput($_POST['phone'] ?? '');
$spaAddress = sanitizeInput($_POST['spa_address'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');

if ($sessionId <= 0 || empty($name) || empty($email) || empty($phone) || empty($spaAddress)) {
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
    exit;
}

if (!validateEmail($email)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

if (!validatePhone($phone)) {
    echo json_encode(['success' => false, 'message' => 'Invalid phone number. Please enter 10 digits']);
    exit;
}

$session = getSessionById($sessionId);
if (!$session || $session['status'] !== 'active') {
    echo json_encode(['success' => false, 'message' => 'Invalid session']);
    exit;
}

$bookingData = [
    'session_id' => $sessionId,
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'spa_address' => $spaAddress,
    'message' => $message
];

$result = createSessionBooking($bookingData);

if ($result['success']) {
    try {
        $emailSubject = "Session Booking Confirmation - " . $session['name'];
        $emailBody = "
            <h2>Session Booking Confirmation</h2>
            <p>Dear $name,</p>
            <p>Thank you for booking with us! We have received your booking request for <strong>" . $session['name'] . "</strong>.</p>

            <h3>Booking Details:</h3>
            <ul>
                <li><strong>Session:</strong> " . $session['name'] . "</li>
                <li><strong>Duration:</strong> " . $session['therapy_time'] . "</li>
                <li><strong>Price:</strong> ₹" . number_format($session['price'], 0) . "</li>
                <li><strong>Location:</strong> $spaAddress</li>
            </ul>

            <p><strong>Your Message:</strong><br>" . nl2br($message) . "</p>

            <p>Our team will contact you shortly to confirm your appointment.</p>

            <h3>Contact Information:</h3>
            <ul>
                <li><strong>Phone:</strong> +91 9560656913 / +91 7005120041</li>
                <li><strong>Email:</strong> karanchourasia2017@gmail.com</li>
            </ul>

            <p>Thank you for choosing our spa services!</p>
        ";

        sendEmail($email, $emailSubject, $emailBody);

        $adminEmailBody = "
            <h2>New Session Booking Received</h2>
            <h3>Customer Details:</h3>
            <ul>
                <li><strong>Name:</strong> $name</li>
                <li><strong>Email:</strong> $email</li>
                <li><strong>Phone:</strong> $phone</li>
            </ul>

            <h3>Booking Details:</h3>
            <ul>
                <li><strong>Session:</strong> " . $session['name'] . "</li>
                <li><strong>Duration:</strong> " . $session['therapy_time'] . "</li>
                <li><strong>Price:</strong> ₹" . number_format($session['price'], 0) . "</li>
                <li><strong>Location:</strong> $spaAddress</li>
            </ul>

            <p><strong>Customer Message:</strong><br>" . nl2br($message) . "</p>

            <p><em>Please contact the customer to confirm the appointment.</em></p>
        ";

        sendEmail(SMTP_USER, "New Session Booking - " . $session['name'], $adminEmailBody);
    } catch (Exception $e) {
    }

    echo json_encode([
        'success' => true,
        'message' => 'Your booking has been submitted successfully! We will contact you shortly to confirm your appointment.',
        'booking_id' => $result['booking_id']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => $result['message'] ?? 'Failed to submit booking. Please try again.'
    ]);
}
?>
