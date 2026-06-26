<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once "../config/database.php";

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email']) || 
        empty($data['phone']) || empty($data['password']) || empty($data['confirmPassword'])) {
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

    if (empty($data['agreeTerms']) || $data['agreeTerms'] !== true) {
        echo json_encode([
            "success" => false,
            "message" => "You must agree to the Terms & Conditions."
        ]);
        exit;
    }

    $firstname = trim($data['firstname']);
    $middlename = isset($data['middlename']) ? trim($data['middlename']) : null;
    $lastname  = trim($data['lastname']);
    $phone     = trim($data['phone']);
    $email     = trim(strtolower($data['email']));
    $password  = $data['password'];
    $role      = "user";

    $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
    $check->bind_param("ss", $email, $phone);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Email or phone number already registered."
        ]);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users (firstname, middlename, lastname, phone, email, password, role) 
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("sssssss", $firstname, $middlename, $lastname, $phone, $email, $hashedPassword, $role);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "User registered successfully. You can now login."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Registration failed. Please try again."
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "An unexpected error occurred. Please try again later."
    ]);
}