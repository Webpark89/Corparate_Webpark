<?php
/**
 * Admin logout — destroys the session, clears remember cookie, and redirects to the login page.
 */
require_once __DIR__ . '/includes/functions.php';
clear_remember_me_cookie();
session_unset();
session_destroy();
header('Location: ' . ADMIN_URL . '/login.php');
