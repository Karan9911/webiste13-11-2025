<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$pageTitle = 'Terms of Service';
?>

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section-inner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Terms of Service</h1>
                <p class="lead mb-4">Please read these terms carefully before using our services.</p>
            </div>
        </div>
    </div>
</section>

<!-- Terms Content -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="content-section">
                    <p class="text-muted mb-4"><strong>Last updated:</strong> <?php echo date('F j, Y'); ?></p>
                    
                    <h3>Acceptance of Terms</h3>
                    <p>By accessing and using Hammam Spa services, you accept and agree to be bound by the terms and provision of this agreement.</p>
                    
                    <h3>Service Description</h3>
                    <p>Hammam Spa provides professional spa and wellness services including:</p>
                    <ul>
                        <li>Therapeutic massage services</li>
                        <li>Wellness consultations</li>
                        <li>Online booking platform</li>
                        <li>Customer support services</li>
                    </ul>
                    
                    <h3>Booking and Cancellation Policy</h3>
                    <ul>
                        <li>Bookings can be made online or by phone</li>
                        <li>Cancellations must be made at least 24 hours in advance</li>
                        <li>Late cancellations may incur charges</li>
                        <li>No-shows will be charged the full service amount</li>
                    </ul>
                    
                    <h3>Payment Terms</h3>
                    <ul>
                        <li>Payment can be made online or at the time of service</li>
                        <li>All prices are in Indian Rupees (INR)</li>
                        <li>Additional charges may apply for night services</li>
                        <li>Refunds are processed according to our refund policy</li>
                    </ul>
                    
                    <h3>User Responsibilities</h3>
                    <p>Users are responsible for:</p>
                    <ul>
                        <li>Providing accurate information</li>
                        <li>Maintaining account security</li>
                        <li>Respecting therapists and staff</li>
                        <li>Following health and safety guidelines</li>
                    </ul>
                    
                    <h3>Limitation of Liability</h3>
                    <p>Hammam Spa shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of our services.</p>
                    
                    <h3>Privacy</h3>
                    <p>Your privacy is important to us. Please review our Privacy Policy to understand how we collect and use your information.</p>
                    
                    <h3>Modifications</h3>
                    <p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on our website.</p>
                    
                    <h3>Contact Information</h3>
                    <p>For questions about these Terms of Service, contact us at:</p>
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