<?php
// Email configuration and functions

// Email settings - configure these for your hosting environment
define('SMTP_ENABLED', true); // Set to true if you want to use SMTP instead of PHP mail()
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'karanchourasia2017@gmail.com'); // Configure if using SMTP
define('SMTP_PASSWORD', 'your-app-password'); // Configure if using SMTP - Replace with actual app password
define('NOTIFICATION_EMAIL', 'karanchourasia2017@gmail.com');

/**
 * Send booking notification email
 */
function sendBookingNotification($bookingData) {
    $to = NOTIFICATION_EMAIL;
    $subject = "New Spa Booking - Booking #" . ($bookingData['booking_id'] ?? 'N/A');
    
    // Create email content
    $message = createBookingEmailContent($bookingData);
    
    // Email headers
    $headers = [
        'From: noreply@boyztown.in',
        'Reply-To: ' . ($bookingData['email'] ?? 'noreply@boyztown.in'),
        'Content-Type: text/html; charset=UTF-8',
        'X-Mailer: PHP/' . phpversion()
    ];
    
    if (SMTP_ENABLED) {
        return sendSMTPEmail($to, $subject, $message, $headers);
    } else {
        return mail($to, $subject, $message, implode("\r\n", $headers));
    }
}

/**
 * Send inquiry notification email
 */
function sendInquiryNotification($inquiryData) {
    $to = NOTIFICATION_EMAIL;
    $subject = "New Spa Inquiry from " . $inquiryData['full_name'];
    
    // Create email content
    $message = createInquiryEmailContent($inquiryData);
    
    // Email headers
    $headers = [
        'From: noreply@boyztown.in',
        'Reply-To: ' . ($inquiryData['email'] ?? 'noreply@boyztown.in'),
        'Content-Type: text/html; charset=UTF-8',
        'X-Mailer: PHP/' . phpversion()
    ];
    
    if (SMTP_ENABLED) {
        return sendSMTPEmail($to, $subject, $message, $headers);
    } else {
        return mail($to, $subject, $message, implode("\r\n", $headers));
    }
}

/**
 * Send contact form notification email
 */
function sendContactNotification($contactData) {
    $to = NOTIFICATION_EMAIL;
    $subject = "New Contact Form Submission - " . ($contactData['subject'] ?? 'General Inquiry');
    
    // Create email content
    $message = createContactEmailContent($contactData);
    
    // Email headers
    $headers = [
        'From: noreply@boyztown.in',
        'Reply-To: ' . ($contactData['email'] ?? 'noreply@boyztown.in'),
        'Content-Type: text/html; charset=UTF-8',
        'X-Mailer: PHP/' . phpversion()
    ];
    
    if (SMTP_ENABLED) {
        return sendSMTPEmail($to, $subject, $message, $headers);
    } else {
        return mail($to, $subject, $message, implode("\r\n", $headers));
    }
}

/**
 * Create booking email content
 */
function createBookingEmailContent($data) {
    $therapistName = $data['therapist_name'] ?? 'N/A';
    $bookingDate = isset($data['booking_date']) ? date('M j, Y', strtotime($data['booking_date'])) : 'N/A';
    $bookingTime = isset($data['booking_time']) ? date('g:i A', strtotime($data['booking_time'])) : 'N/A';
    $totalAmount = isset($data['total_amount']) ? formatPrice($data['total_amount']) : 'N/A';
    $paymentMethod = isset($data['payment_id']) && $data['payment_id'] ? 'Online Payment' : 'Pay Later';
    $region = isset($data['region']) ? ($data['region'] === 'ncr' ? 'Delhi-NCR' : 'Rest of India') : 'N/A';
    $isNight = isset($data['is_night']) && $data['is_night'] ? 'Yes (+₹1,500)' : 'No';
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>New Booking Notification</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #2E8B57; color: white; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; }
            .booking-details { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
            .detail-row { margin: 8px 0; }
            .label { font-weight: bold; color: #2E8B57; }
            .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
            .urgent { background: #ff6b6b; color: white; padding: 10px; text-align: center; margin-bottom: 20px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🎉 New Spa Booking Received!</h1>
                <p>Booking ID: #' . ($data['booking_id'] ?? 'N/A') . '</p>
            </div>
            
            <div class="urgent">
                <strong>⚡ URGENT: New booking requires immediate attention!</strong>
            </div>
            
            <div class="content">
                <h2>Customer Information</h2>
                <div class="booking-details">
                    <div class="detail-row">
                        <span class="label">Name:</span> ' . htmlspecialchars($data['full_name'] ?? 'N/A') . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Email:</span> ' . htmlspecialchars($data['email'] ?? 'N/A') . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Phone:</span> ' . htmlspecialchars($data['phone'] ?? 'N/A') . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Address:</span> ' . htmlspecialchars($data['address'] ?? 'N/A') . '
                    </div>
                </div>
                
                <h2>Booking Details</h2>
                <div class="booking-details">
                    <div class="detail-row">
                        <span class="label">Therapist:</span> ' . htmlspecialchars($therapistName) . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Date:</span> ' . $bookingDate . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Time:</span> ' . $bookingTime . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Region:</span> ' . $region . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Night Service:</span> ' . $isNight . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Total Amount:</span> <strong>' . $totalAmount . '</strong>
                    </div>
                    <div class="detail-row">
                        <span class="label">Payment Method:</span> ' . $paymentMethod . '
                    </div>
                </div>';
    
    if (!empty($data['message'])) {
        $html .= '
                <h2>Special Requests</h2>
                <div class="booking-details">
                    ' . nl2br(htmlspecialchars($data['message'])) . '
                </div>';
    }
    
    $html .= '
                <div style="margin-top: 20px; padding: 15px; background: #e8f5e8; border-radius: 5px;">
                    <h3 style="margin: 0; color: #2E8B57;">Next Steps:</h3>
                    <ul style="margin: 10px 0;">
                        <li>Contact the customer to confirm the booking</li>
                        <li>Assign the therapist for the scheduled time</li>
                        <li>Update booking status in admin panel</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer">
                <p>This email was sent from your Hammam Spa booking system.</p>
                <p>Login to admin panel: <a href="https://boyztown.in/admin/" style="color: #4CAF50;">https://boyztown.in/admin/</a></p>
            </div>
        </div>
    </body>
    </html>';
    
    return $html;
}

/**
 * Create inquiry email content
 */
function createInquiryEmailContent($data) {
    $therapistName = $data['therapist_name'] ?? 'General Inquiry';
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>New Inquiry Notification</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #3CB371; color: white; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; }
            .inquiry-details { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
            .detail-row { margin: 8px 0; }
            .label { font-weight: bold; color: #3CB371; }
            .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>💬 New Inquiry Received!</h1>
                <p>From: ' . htmlspecialchars($data['full_name'] ?? 'N/A') . '</p>
            </div>
            
            <div class="content">
                <h2>Customer Information</h2>
                <div class="inquiry-details">
                    <div class="detail-row">
                        <span class="label">Name:</span> ' . htmlspecialchars($data['full_name'] ?? 'N/A') . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Email:</span> ' . htmlspecialchars($data['email'] ?? 'N/A') . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Phone:</span> ' . htmlspecialchars($data['phone'] ?? 'N/A') . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Interested in:</span> ' . htmlspecialchars($therapistName) . '
                    </div>
                </div>
                
                <h2>Message</h2>
                <div class="inquiry-details">
                    ' . nl2br(htmlspecialchars($data['message'] ?? 'No message provided')) . '
                </div>
                
                <div style="margin-top: 20px; padding: 15px; background: #e8f5e8; border-radius: 5px;">
                    <h3 style="margin: 0; color: #3CB371;">Recommended Actions:</h3>
                    <ul style="margin: 10px 0;">
                        <li>Respond to the customer within 2 hours</li>
                        <li>Provide detailed information about services</li>
                        <li>Follow up to convert inquiry to booking</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer">
                <p>This email was sent from your Hammam Spa inquiry system.</p>
                <p>Manage leads: <a href="https://boyztown.in/admin/leads.php" style="color: #4CAF50;">https://boyztown.in/admin/leads.php</a></p>
            </div>
        </div>
    </body>
    </html>';
    
    return $html;
}

/**
 * Create contact email content
 */
function createContactEmailContent($data) {
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>New Contact Form Submission</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #DAA520; color: white; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; }
            .contact-details { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
            .detail-row { margin: 8px 0; }
            .label { font-weight: bold; color: #DAA520; }
            .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>📧 New Contact Form Submission</h1>
                <p>Subject: ' . htmlspecialchars($data['subject'] ?? 'General Inquiry') . '</p>
            </div>
            
            <div class="content">
                <h2>Contact Information</h2>
                <div class="contact-details">
                    <div class="detail-row">
                        <span class="label">Name:</span> ' . htmlspecialchars($data['name'] ?? 'N/A') . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Email:</span> ' . htmlspecialchars($data['email'] ?? 'N/A') . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Phone:</span> ' . htmlspecialchars($data['phone'] ?? 'N/A') . '
                    </div>
                    <div class="detail-row">
                        <span class="label">Subject:</span> ' . htmlspecialchars($data['subject'] ?? 'N/A') . '
                    </div>
                </div>
                
                <h2>Message</h2>
                <div class="contact-details">
                    ' . nl2br(htmlspecialchars($data['message'] ?? 'No message provided')) . '
                </div>
            </div>
            
            <div class="footer">
                <p>This email was sent from your Hammam Spa contact form.</p>
                <p>Respond directly to: ' . htmlspecialchars($data['email'] ?? 'N/A') . '</p>
            </div>
        </div>
    </body>
    </html>';
    
    return $html;
}

/**
 * Enhanced SMTP email function using PHPMailer-like functionality
 */
function sendSMTPEmail($to, $subject, $message, $headers) {
    if (!SMTP_ENABLED) {
        return mail($to, $subject, $message, implode("\r\n", $headers));
    }
    
    // Enhanced SMTP implementation
    $boundary = md5(time());
    
    // Prepare headers
    $smtp_headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . SMTP_USERNAME,
        'Reply-To: ' . SMTP_USERNAME,
        'X-Mailer: PHP/' . phpversion()
    ];
    
    // Use mail() function with enhanced headers for now
    // In production, implement full SMTP authentication
    return mail($to, $subject, $message, implode("\r\n", $smtp_headers));
}

/**
 * Test email functionality
 */
function testEmailConfiguration() {
    $testData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'message' => 'This is a test email to verify email configuration.'
    ];
    
    $subject = 'Email Configuration Test - Hammam Spa';
    $message = createContactEmailContent($testData);
    
    $headers = [
        'From: noreply@boyztown.in',
        'Reply-To: test@example.com',
        'Content-Type: text/html; charset=UTF-8',
        'X-Mailer: PHP/' . phpversion()
    ];
    
    if (SMTP_ENABLED) {
        return sendSMTPEmail(NOTIFICATION_EMAIL, $subject, $message, $headers);
    } else {
        return mail(NOTIFICATION_EMAIL, $subject, $message, implode("\r\n", $headers));
    }
}

/**
 * Simple honeypot spam protection
 */
function validateHoneypot($honeypotField = 'website') {
    return empty($_POST[$honeypotField]);
}

/**
 * Basic rate limiting (simple implementation)
 */
function checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 300) {
    $rateLimitFile = sys_get_temp_dir() . '/spa_rate_limit_' . md5($identifier);
    
    if (file_exists($rateLimitFile)) {
        $data = json_decode(file_get_contents($rateLimitFile), true);
        $currentTime = time();
        
        // Clean old attempts
        $data['attempts'] = array_filter($data['attempts'], function($timestamp) use ($currentTime, $timeWindow) {
            return ($currentTime - $timestamp) < $timeWindow;
        });
        
        if (count($data['attempts']) >= $maxAttempts) {
            return false; // Rate limit exceeded
        }
        
        $data['attempts'][] = $currentTime;
    } else {
        $data = ['attempts' => [time()]];
    }
    
    file_put_contents($rateLimitFile, json_encode($data));
    return true;
}
?>