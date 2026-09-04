<?php

/**
 * AJAX Heartbeat Endpoint
 * Keeps admin session active and returns refreshed CSRF token.
 */
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/functions.php';

if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode([
        'authenticated' => false,
        'message' => 'Session expired or not logged in.'
    ]);
    exit;
}

// Update activity time
$_SESSION['last_activity'] = time();

echo json_encode([
    'authenticated' => true,
    'csrf_token' => csrf_token(),
    'timestamp' => time(),
    'admin_username' => $_SESSION['admin_username'] ?? ''
]);
exit;
