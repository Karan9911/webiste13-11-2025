<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$imageId = (int)($input['image_id'] ?? 0);

if ($imageId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid image ID']);
    exit;
}

try {
    $db = getDB();
    
    // Get image details before deletion
    $stmt = $db->prepare("SELECT * FROM therapist_images WHERE id = ?");
    $stmt->execute([$imageId]);
    $image = $stmt->fetch();
    
    if (!$image) {
        echo json_encode(['success' => false, 'message' => 'Image not found']);
        exit;
    }
    
    // Delete image file
    deleteImage($image['image_path']);
    
    // Delete from database
    $stmt = $db->prepare("DELETE FROM therapist_images WHERE id = ?");
    $result = $stmt->execute([$imageId]);
    
    if ($result) {
        // If this was the main image, update therapist table
        if ($image['is_main']) {
            $stmt = $db->prepare("UPDATE therapists SET main_image = NULL WHERE id = ?");
            $stmt->execute([$image['therapist_id']]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Image removed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove image']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}
?>