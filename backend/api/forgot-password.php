<?php

require_once "../config/database.php";
require "../emails/reset-password-email.php";

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
    http_response_code(200);
    exit;
}

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['email'])) {
        echo json_encode([
            "success" => false,
            "message" => "Email is required."
        ]);
        exit;
    }

    $email = trim(strtolower($data['email']));

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        echo json_encode([
            "success" => false,
            "message" => "No account found with this email."
        ]);
        exit;
    }

    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $expire = date("Y-m-d H:i:s", strtotime("+30 minutes"));

    // Update user with reset token
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expire = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expire, $email);
    $stmt->execute();

    $link = "http://localhost:5173/auth/reset-password?token=" . $token;

    if (sendResetPasswordEmail($email, $link)) {
        echo json_encode([
            "success" => true,
            "message" => "Password reset link has been sent to your email."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to send reset email. Please try again later."
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "An unexpected error occurred. Please try again later."
    ]);
}