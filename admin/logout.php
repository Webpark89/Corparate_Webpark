<?php
/**
 * Admin logout — destroys the session and redirects to the login page.
 */
require_once __DIR__ . '/includes/functions.php';
session_unset();
session_destroy();
header('Location: ' . ADMIN_URL . '/login.php');
