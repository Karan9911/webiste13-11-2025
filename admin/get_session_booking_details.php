<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

header('Content-Type: application/json');

$bookingId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($bookingId > 0) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT sb.*, ss.name as session_name
        FROM session_bookings sb
        LEFT JOIN spa_sessions ss ON sb.session_id = ss.id
        WHERE sb.id = ?
    ");
    $stmt->execute([$bookingId]);
    $booking = $stmt->fetch();

    if ($booking) {
        echo json_encode([
            'success' => true,
            'booking' => $booking
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Booking not found'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid booking ID'
    ]);
}
?>
