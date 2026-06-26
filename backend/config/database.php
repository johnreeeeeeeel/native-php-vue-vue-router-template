<?php
date_default_timezone_set("Asia/Manila");

require __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$conn = new mysqli(
    $_ENV['DATABASE_HOSTNAME'],
    $_ENV['DATABASE_USERNAME'],
    $_ENV['DATABASE_PASSWORD'],
    $_ENV['DATABASE_NAME']
);

if ($conn->connect_error) {
    header("Content-Type: application/json");

    die(json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]));
}
?>