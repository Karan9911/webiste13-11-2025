<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid therapist ID']);
    exit;
}

$therapistId = (int)$_GET['id'];

try {
    $images = getTherapistImages($therapistId);
    
    echo json_encode([
        'success' => true,
        'images' => $images
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}
?>