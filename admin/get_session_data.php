<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

header('Content-Type: application/json');

$sessionId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($sessionId > 0) {
    $session = getSessionById($sessionId);

    if ($session) {
        echo json_encode([
            'success' => true,
            'session' => $session
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Session not found'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid session ID'
    ]);
}
?>
