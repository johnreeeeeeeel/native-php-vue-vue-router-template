<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once "../config/database.php";

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['token']) || empty($data['password']) || empty($data['confirmPassword'])) {
        echo json_encode([
            "success" => false,
            "message" => "All fields are required."
        ]);
        exit;
    }

    if ($data['password'] !== $data['confirmPassword']) {
        echo json_encode([
            "success" => false,
            "message" => "Passwords do not match."
        ]);
        exit;
    }

    if (strlen($data['password']) < 6) {
        echo json_encode([
            "success" => false,
            "message" => "Password must be at least 6 characters long."
        ]);
        exit;
    }

    $token = $data['token'];
    $password = $data['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "SELECT id FROM users WHERE reset_token = ? AND reset_expire > NOW()"
    );
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid or expired reset token."
        ]);
        exit;
    }

    $stmt = $conn->prepare(
        "UPDATE users SET password = ?, reset_token = NULL, reset_expire = NULL WHERE id = ?"
    );
    $stmt->bind_param("si", $hashedPassword, $user['id']);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Password has been reset successfully."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to reset password. Please try again."
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "An unexpected error occurred. Please try again later."
    ]);
}