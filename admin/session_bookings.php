<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$pageTitle = 'Session Bookings';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $bookingId = (int)$_POST['booking_id'];

    if ($action === 'update_status') {
        $status = $_POST['status'];
        $db = getDB();

        try {
            $stmt = $db->prepare("UPDATE session_bookings SET status = ? WHERE id = ?");
            $stmt->execute([$status, $bookingId]);
            $message = 'Booking status updated successfully!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error updating status: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

$bookings = getAllSessionBookings();
?>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Session Bookings</h2>
            <p class="text-muted mb-0">Manage all session booking requests</p>
        </div>
    </div>

    <?php if (isset($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
            <i class="bi bi-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if (empty($bookings)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-4 text-muted"></i>
                    <h5 class="text-muted mt-3">No session bookings found</h5>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Session</th>
                                <th>Customer</th>
                                <th>Contact</th>
                                <th>Spa Address</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><strong>#<?php echo $booking['id']; ?></strong></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($booking['session_name']); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($booking['name']); ?>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($booking['email']); ?><br>
                                            <i class="bi bi-telephone"></i> <?php echo htmlspecialchars($booking['phone']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <small><?php echo htmlspecialchars(substr($booking['spa_address'], 0, 50)); ?>...</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php
                                        echo $booking['status'] === 'pending' ? 'warning' :
                                            ($booking['status'] === 'confirmed' ? 'success' :
                                            ($booking['status'] === 'cancelled' ? 'danger' : 'info'));
                                        ?>">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?php echo timeAgo($booking['created_at']); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewBooking(<?php echo $booking['id']; ?>)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="updateStatus(<?php echo $booking['id']; ?>, 'confirmed')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- View Booking Modal -->
<div class="modal fade" id="viewBookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Form -->
<form id="updateStatusForm" method="POST" style="display: none;">
    <input type="hidden" name="action" value="update_status">
    <input type="hidden" name="booking_id" id="statusBookingId">
    <input type="hidden" name="status" id="statusValue">
</form>

<?php
$extraScripts = '<script>
    function viewBooking(id) {
        const modal = new bootstrap.Modal(document.getElementById("viewBookingModal"));
        modal.show();

        fetch("get_session_booking_details.php?id=" + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const booking = data.booking;
                    document.getElementById("bookingDetailsContent").innerHTML = `
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-1">Booking ID</h6>
                                <p class="fw-bold">#${booking.id}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-1">Session</h6>
                                <p class="fw-bold">${booking.session_name}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-1">Customer Name</h6>
                                <p>${booking.name}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-1">Email</h6>
                                <p>${booking.email}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-1">Phone</h6>
                                <p>${booking.phone}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-1">Status</h6>
                                <p><span class="badge bg-primary">${booking.status}</span></p>
                            </div>
                            <div class="col-12 mb-3">
                                <h6 class="text-muted mb-1">Spa Address</h6>
                                <p>${booking.spa_address}</p>
                            </div>
                            <div class="col-12 mb-3">
                                <h6 class="text-muted mb-1">Message</h6>
                                <p>${booking.message || "No message provided"}</p>
                            </div>
                            <div class="col-12">
                                <h6 class="text-muted mb-1">Booked On</h6>
                                <p>${new Date(booking.created_at).toLocaleString()}</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="booking_id" value="${booking.id}">
                                <select name="status" class="form-select d-inline-block w-auto me-2">
                                    <option value="pending" ${booking.status === "pending" ? "selected" : ""}>Pending</option>
                                    <option value="confirmed" ${booking.status === "confirmed" ? "selected" : ""}>Confirmed</option>
                                    <option value="cancelled" ${booking.status === "cancelled" ? "selected" : ""}>Cancelled</option>
                                    <option value="completed" ${booking.status === "completed" ? "selected" : ""}>Completed</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </form>
                        </div>
                    `;
                } else {
                    document.getElementById("bookingDetailsContent").innerHTML = `
                        <div class="alert alert-danger">Error loading booking details</div>
                    `;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                document.getElementById("bookingDetailsContent").innerHTML = `
                    <div class="alert alert-danger">Error loading booking details</div>
                `;
            });
    }

    function updateStatus(id, status) {
        if (confirm("Update booking status to " + status + "?")) {
            document.getElementById("statusBookingId").value = id;
            document.getElementById("statusValue").value = status;
            document.getElementById("updateStatusForm").submit();
        }
    }
</script>';

include 'includes/admin_footer.php';
?>
