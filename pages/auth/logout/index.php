<?php

session_start();

// Clear all session variables
session_unset();

// Destroy session
session_destroy();

// Redirect to login page
header("Location: /auth/login");

exit;