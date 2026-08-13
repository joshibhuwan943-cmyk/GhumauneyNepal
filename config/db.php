<?php
/**
 * Database connection (procedural mysqli)
 * ----------------------------------------
 * Every backend endpoint pulls in this file to get a $conn resource.
 * Update the four values below to match your local MySQL / XAMPP / WAMP setup.
 */

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "ghumauneynepal";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed: " . mysqli_connect_error()
    ]);
    exit;
}

mysqli_set_charset($conn, "utf8mb4");