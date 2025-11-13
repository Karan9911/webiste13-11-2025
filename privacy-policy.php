<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$pageTitle = 'Privacy Policy';
?>

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section-inner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Privacy Policy</h1>
                <p class="lead mb-4">Your privacy is important to us. This policy explains how we collect, use, and protect your information.</p>
            </div>
        </div>
    </div>
</section>

<!-- Privacy Policy Content -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="content-section">
                    <p class="text-muted mb-4"><strong>Last updated:</strong> <?php echo date('F j, Y'); ?></p>
                    
                    <h3>Information We Collect</h3>
                    <p>We collect information you provide directly to us, such as when you:</p>
                    <ul>
                        <li>Create an account or book a service</li>
                        <li>Contact us for support or inquiries</li>
                        <li>Subscribe to our newsletter</li>
                        <li>Provide feedback or reviews</li>
                    </ul>
                    
                    <h3>How We Use Your Information</h3>
                    <p>We use the information we collect to:</p>
                    <ul>
                        <li>Provide, maintain, and improve our services</li>
                        <li>Process bookings and payments</li>
                        <li>Send you confirmations and updates</li>
                        <li>Respond to your comments and questions</li>
                        <li>Ensure the security of our platform</li>
                    </ul>
                    
                    <h3>Information Sharing</h3>
                    <p>We do not sell, trade, or otherwise transfer your personal information to third parties except:</p>
                    <ul>
                        <li>With your explicit consent</li>
                        <li>To trusted service providers who assist us</li>
                        <li>When required by law or to protect our rights</li>
                    </ul>
                    
                    <h3>Data Security</h3>
                    <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
                    
                    <h3>Your Rights</h3>
                    <p>You have the right to:</p>
                    <ul>
                        <li>Access your personal information</li>
                        <li>Correct inaccurate information</li>
                        <li>Request deletion of your information</li>
                        <li>Opt-out of marketing communications</li>
                    </ul>
                    
                    <h3>Cookies</h3>
                    <p>We use cookies to enhance your experience on our website. You can control cookie settings through your browser preferences.</p>
                    
                    <h3>Contact Us</h3>
                    <p>If you have any questions about this Privacy Policy, please contact us at:</p>
                    <ul>
                        <li>Email: info@hammammensspa.com</li>
                        <li>Phone: +91 7005120041</li>
                        <li>Address: A-35 Block A2, Rajouri Garden New Delhi, 110027</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>