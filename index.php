<?php

$requestUri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

// Map URL to public folder
$publicFile = __DIR__ . '/public' . $requestUri;

// Serve static file if it exists
if (
    $requestUri !== '/' &&
    file_exists($publicFile) &&
    !is_dir($publicFile)
) {

    // Manually output file
    $mime = mime_content_type($publicFile);

    header("Content-Type: $mime");

    readfile($publicFile);

    exit;
}

// Continue app
require_once __DIR__ . '/public/index.php';