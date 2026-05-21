<?php

// Added /health routes to be ping by https://cron-job.org to keep aiven.io free mysql from sleeping

require_once __DIR__ . '/../../database/Database.php';

$db = new Database();

$conn = $db->getConnection();

$result = $conn->query("SELECT 1");

echo "OK";