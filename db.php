<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'portfolio_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
}

$conn->set_charset('utf8mb4');
