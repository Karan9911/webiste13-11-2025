<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$pageTitle = 'Our Services';
$services = getAllServices();
?>

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section-inner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Our Premium Services</h1>
                <p class="lead mb-4">Discover our comprehensive range of therapeutic and wellness services designed to rejuvenate your mind, body, and soul.</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="py-5 bg-white">
    <div class="container">
        <?php if (empty($services)): ?>
            <div class="text-center py-5">
                <i class="bi bi-gear display-4 text-muted"></i>
                <h5 class="text-muted mt-3">No services available</h5>
                <p class="text-muted">Services will be added soon.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php 
                $serviceIcons = [
                    'Swedish Massage' => 'bi-heart-pulse',
                    'Deep Tissue Massage' => 'bi-activity',
                    'Hot Stone Therapy' => 'bi-fire',
                    'Aromatherapy' => 'bi-flower1',
                    'Reflexology' => 'bi-hand-thumbs-up',
                    'Thai Massage' => 'bi-person-arms-up'
                ];
                
                foreach ($services as $service): 
                    $icon = $serviceIcons[$service['name']] ?? 'bi-spa';
                    
                    // Use service icon if available
                    if ($service['icon_type'] === 'bootstrap' && $service['icon_value']) {
                        $icon = $service['icon_value'];
                    }
                    
                    // Parse points
                    $points = [];
                    if (!empty($service['points'])) {
                        $points = explode('|', $service['points']);
                    }
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card-detailed">
                            <div class="service-icon-large">
                                <?php if ($service['icon_type'] === 'upload' && $service['icon_image']): ?>
                                    <img src="<?php echo UPLOAD_URL . $service['icon_image']; ?>" 
                                         alt="<?php echo htmlspecialchars($service['name']); ?>" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <i class="bi <?php echo $icon; ?>"></i>
                                <?php endif; ?>
                            </div>
                            <h4 class="service-title"><?php echo htmlspecialchars($service['name']); ?></h4>
                            <p class="service-description"><?php echo htmlspecialchars($service['description']); ?></p>
                            
                            <?php if (!empty($points)): ?>
                                <div class="service-features">
                                    <ul>
                                        <?php foreach ($points as $point): ?>
                                            <li><i class="bi bi-check-circle text-success me-2"></i><?php echo htmlspecialchars(trim($point)); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <div class="service-action">
                                <a href="models.php" class="btn btn-primary">
                                    <i class="bi bi-people me-2"></i>View Therapists
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Why Choose Our Services -->
<section class="py-5 services-section">
    <div class="container">
        <h2 class="section-title display-5 fw-bold">Why Choose Our Services</h2>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h5>Certified Professionals</h5>
                    <p>All our therapists are certified and experienced professionals.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5>Safe & Hygienic</h5>
                    <p>We maintain the highest standards of cleanliness and safety.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <h5>Flexible Timing</h5>
                    <p>Book appointments at your convenient time, 7 days a week.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h5>Personalized Care</h5>
                    <p>Each treatment is customized to your specific needs and preferences.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>