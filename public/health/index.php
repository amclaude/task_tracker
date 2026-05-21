<?php

require_once __DIR__ . '/../../database/Database.php';

$db = new Database();

$conn = $db->getConnection();

$result = $conn->query("SELECT 1");

echo "OK";