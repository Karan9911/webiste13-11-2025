<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$pageTitle = 'Sitemap';
?>

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section-inner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Sitemap</h1>
                <p class="lead mb-4">Find all pages and sections of our website easily.</p>
            </div>
        </div>
    </div>
</section>

<!-- Sitemap Content -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="sitemap-section">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-house me-2"></i>Main Pages
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>" class="text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/about.php" class="text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/contact.php" class="text-decoration-none">Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="sitemap-section">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-spa me-2"></i>Services
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/services.php" class="text-decoration-none">All Services</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/therapies.php" class="text-decoration-none">Therapies</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/models.php" class="text-decoration-none">Our Therapists</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="sitemap-section">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-person me-2"></i>User Account
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/login.php" class="text-decoration-none">Login</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/signup.php" class="text-decoration-none">Sign Up</a></li>
                        <?php if (isUserLoggedIn()): ?>
                            <li class="mb-2"><a href="<?php echo SITE_URL; ?>/profile.php" class="text-decoration-none">My Profile</a></li>
                            <li class="mb-2"><a href="<?php echo SITE_URL; ?>/my-bookings.php" class="text-decoration-none">My Bookings</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="sitemap-section">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-shield-check me-2"></i>Legal
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/privacy-policy.php" class="text-decoration-none">Privacy Policy</a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/terms-of-service.php" class="text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="sitemap-section">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-gear me-2"></i>Admin
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/admin/login.php" class="text-decoration-none">Admin Login</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="sitemap-section">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-info-circle me-2"></i>Help
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/sitemap.php" class="text-decoration-none">Sitemap</a></li>
                        <li class="mb-2"><a href="tel:+917005120041" class="text-decoration-none">Call Support</a></li>
                        <li class="mb-2"><a href="mailto:info@hammammensspa.com" class="text-decoration-none">Email Support</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>