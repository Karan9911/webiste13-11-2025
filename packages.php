<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$pageTitle = 'Spa Packages';
$selectedLocation = $_GET['location'] ?? null;
$sessions = [];

if ($selectedLocation && in_array($selectedLocation, ['location1', 'location2', 'location3'])) {
    $sessions = getAllSessions('active');
}
?>

<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold mb-3">Spa Packages</h1>
            <p class="lead text-muted">Select a location to explore our premium spa session packages</p>
        </div>

        <?php if (!$selectedLocation): ?>
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6 mx-auto" style="max-width: 400px;">
                    <div class="location-selection-container">
                        <h3 class="text-center mb-4 fw-bold">Choose Your Location</h3>

                        <div class="location-card-wrapper">
                            <a href="packages.php?location=location1" class="location-card">
                                <div class="location-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <h5 class="fw-bold">Nehru Place</h5>
                                <small class="text-muted d-block">Shop No. 5, Ground Floor</small>
                                <small class="text-muted d-block">Eros Hotel, New Delhi</small>
                            </a>

                            <a href="packages.php?location=location2" class="location-card mt-3">
                                <div class="location-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <h5 class="fw-bold">Rajouri Garden</h5>
                                <small class="text-muted d-block">Shop No. 92, First Floor</small>
                                <small class="text-muted d-block">Global Mall, New Delhi</small>
                            </a>

                            <a href="packages.php?location=location3" class="location-card mt-3">
                                <div class="location-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <h5 class="fw-bold">Vasant Kunj</h5>
                                <small class="text-muted d-block">2nd Floor, Ambience Mall</small>
                                <small class="text-muted d-block">New Delhi</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="mb-4">
                <a href="packages.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Change Location
                </a>
            </div>

            <?php
            $locationNames = [
                'location1' => 'Nehru Place',
                'location2' => 'Rajouri Garden',
                'location3' => 'Vasant Kunj'
            ];
            ?>

            <div class="text-center mb-5">
                <h3 class="fw-bold text-primary">
                    <i class="bi bi-geo-alt-fill me-2"></i>Packages at <?php echo $locationNames[$selectedLocation] ?? ''; ?>
                </h3>
            </div>

            <?php if (empty($sessions)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No packages available</h4>
                    <p class="text-muted">Please check back later</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($sessions as $session): ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="session-card-package">
                                <div class="session-image-wrapper">
                                    <?php if ($session['image']): ?>
                                        <img src="<?php echo UPLOAD_URL . $session['image']; ?>"
                                             alt="<?php echo htmlspecialchars($session['name']); ?>"
                                             class="img-fluid session-card-image">
                                    <?php else: ?>
                                        <img src="https://images.pexels.com/photos/3757942/pexels-photo-3757942.jpeg?auto=compress&cs=tinysrgb&w=600"
                                             alt="<?php echo htmlspecialchars($session['name']); ?>"
                                             class="img-fluid session-card-image">
                                    <?php endif; ?>
                                </div>

                                <div class="session-card-content">
                                    <h5 class="session-card-title fw-bold"><?php echo htmlspecialchars($session['name']); ?></h5>

                                    <div class="session-meta-info mb-3">
                                        <div class="meta-row">
                                            <i class="bi bi-clock-history text-primary"></i>
                                            <span><?php echo htmlspecialchars($session['therapy_time']); ?></span>
                                        </div>
                                        <div class="meta-row">
                                            <i class="bi bi-currency-rupee text-success"></i>
                                            <span class="session-price-large">₹<?php echo number_format($session['price'], 0); ?></span>
                                        </div>
                                    </div>

                                    <p class="session-card-description"><?php echo htmlspecialchars(substr($session['description'], 0, 80)); ?>...</p>

                                    <div class="session-card-actions">
                                        <a href="session-details.php?id=<?php echo $session['id']; ?>" class="btn btn-primary w-100">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a>
                                        <a href="session-details.php?id=<?php echo $session['id']; ?>#sessionBookingForm" class="btn btn-outline-primary w-100 mt-2">
                                            <i class="bi bi-calendar-check me-2"></i>Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<style>
.location-card {
    display: block;
    padding: 1.5rem;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    text-align: center;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    cursor: pointer;
}

.location-card:hover {
    border-color: var(--primary-color);
    background-color: #f8f9fa;
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.location-icon {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.session-card-package {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.session-card-package:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    transform: translateY(-6px);
}

.session-image-wrapper {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: #f0f0f0;
}

.session-card-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.session-card-package:hover .session-card-image {
    transform: scale(1.05);
}

.session-card-content {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.session-card-title {
    color: #333;
    margin-bottom: 0.75rem;
    font-size: 1.25rem;
}

.session-meta-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.meta-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
}

.session-price-large {
    font-weight: bold;
    font-size: 1.1rem;
    color: var(--primary-color);
}

.session-card-description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    flex-grow: 1;
}

.session-card-actions {
    margin-top: auto;
}

.session-card-actions .btn {
    font-size: 0.9rem;
}
</style>

<?php include 'includes/footer.php'; ?>
