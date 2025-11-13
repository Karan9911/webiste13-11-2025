<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$sessionId = (int)$_GET['id'];
$session = getSessionById($sessionId);

if (!$session || $session['status'] !== 'active') {
    header('Location: index.php');
    exit;
}

$pageTitle = $session['name'] . ' - Session Details';
?>

<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="session-detail-image">
                    <?php if ($session['image']): ?>
                        <img src="<?php echo UPLOAD_URL . $session['image']; ?>"
                             alt="<?php echo htmlspecialchars($session['name']); ?>"
                             class="img-fluid rounded-lg">
                    <?php else: ?>
                        <img src="https://images.pexels.com/photos/3757942/pexels-photo-3757942.jpeg?auto=compress&cs=tinysrgb&w=800"
                             alt="<?php echo htmlspecialchars($session['name']); ?>"
                             class="img-fluid rounded-lg">
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="session-detail-info">
                    <h1 class="session-detail-title"><?php echo htmlspecialchars($session['name']); ?></h1>

                    <div class="session-meta mb-4">
                        <div class="meta-item">
                            <i class="bi bi-clock text-primary me-2"></i>
                            <strong>Duration:</strong> <?php echo htmlspecialchars($session['therapy_time']); ?>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-currency-rupee text-primary me-2"></i>
                            <strong>Price:</strong> <span class="session-price">₹<?php echo number_format($session['price'], 0); ?></span>
                        </div>
                    </div>

                    <div class="session-description mb-4">
                        <h5>About This Session</h5>
                        <p><?php echo nl2br(htmlspecialchars($session['description'])); ?></p>
                    </div>

                    <div class="contact-details mb-4">
                        <h5>Contact Information</h5>
                        <div class="contact-item">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <a href="tel:+919560656913">+91 9560656913</a> / <a href="tel:+917005120041">+91 7005120041</a>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-whatsapp text-success me-2"></i>
                            <a href="https://wa.me/919560656913" target="_blank">WhatsApp</a>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <a href="mailto:karanchourasia2017@gmail.com">karanchourasia2017@gmail.com</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="session-booking-form-container">
                    <h3 class="text-center mb-4">Book This Session</h3>

                    <div id="bookingAlert"></div>

                    <form id="sessionBookingForm" method="POST" action="process_session_booking.php">
                        <input type="hidden" name="session_id" value="<?php echo $sessionId; ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name *</label>
                            <input type="text" class="form-control" name="name" id="bookingName" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email *</label>
                                <input type="email" class="form-control" name="email" id="bookingEmail" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone *</label>
                                <input type="tel" class="form-control" name="phone" id="bookingPhone" pattern="[0-9]{10}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Spa Address *</label>
                            <select class="form-control" name="spa_address" id="spaAddress" required>
                                <option value="">Choose a location</option>
                                <option value="Shop No. 5, Ground Floor, Eros Hotel, Nehru Place, New Delhi, Delhi 110019">Shop No. 5, Ground Floor, Eros Hotel, Nehru Place, New Delhi, Delhi 110019</option>
                                <option value="Shop No. 92, First Floor, Global Mall, Rajouri Garden, New Delhi, Delhi 110027">Shop No. 92, First Floor, Global Mall, Rajouri Garden, New Delhi, Delhi 110027</option>
                                <option value="2nd Floor, Ambience Mall, Vasant Kunj, New Delhi, Delhi 110070">2nd Floor, Ambience Mall, Vasant Kunj, New Delhi, Delhi 110070</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Message / Special Request</label>
                            <textarea class="form-control" name="message" id="bookingMessage" rows="4" placeholder="Any special requirements or preferences..."></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-calendar-check me-2"></i>Submit Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('sessionBookingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

    const formData = new FormData(this);

    fetch('process_session_booking.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const alertDiv = document.getElementById('bookingAlert');

        if (data.success) {
            alertDiv.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.getElementById('sessionBookingForm').reset();
        } else {
            alertDiv.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }

        alertDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });

        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    })
    .catch(error => {
        console.error('Error:', error);
        const alertDiv = document.getElementById('bookingAlert');
        alertDiv.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>
                An error occurred. Please try again.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
});
</script>

<?php include 'includes/footer.php'; ?>
