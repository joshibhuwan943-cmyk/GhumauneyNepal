<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

$email = get_value("email");
$password = get_value("password");

/* Validate input */
if ($email === "" || $password === "") {
    send_response(false, "Email and password are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_response(false, "Invalid email address.");
}

/* Find user */
$stmt = mysqli_prepare(
    $conn,
    "SELECT id, name, email, password, role
     FROM users
     WHERE email = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

/* Check user */
if (!$user) {
    send_response(false, "Invalid email or password.");
}

/* Verify password */
if (!password_verify($password, $user["password_hash"])) {
    send_response(false, "Invalid email or password.");
}

/* Create new session ID */
session_regenerate_id(true);

/* Store user information in session */
$_SESSION["user_id"] = (int) $user["id"];
$_SESSION["name"] = $user["name"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];

/* Send success response */
send_response(
    true,
    "Login successful.",
    [
        "user_id" => (int) $user["id"],
        "name" => $user["name"],
        "email" => $user["email"],
        "role" => $user["role"]
    ]
);