<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

$name = get_value("name");
$email = get_value("email");
$password = get_value("password");

/* Validate input */
if ($name === "" || $email === "" || $password === "") {
    send_response(false, "All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_response(false, "Invalid email address.");
}

if (strlen($password) < 6) {
    send_response(false, "Password must be at least 6 characters.");
}

/* Check whether email already exists */
$stmt = mysqli_prepare(
    $conn,
    "SELECT id FROM users WHERE email = ? LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    mysqli_stmt_close($stmt);
    send_response(false, "Email is already registered.");
}

mysqli_stmt_close($stmt);

/* Hash password */
$password_hash = password_hash($password, PASSWORD_DEFAULT);

/* Insert user */
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO users (name, email, password, role)
     VALUES (?, ?, ?, 'user')"
);

mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $name,
    $email,
    $password_hash
);

if (mysqli_stmt_execute($stmt)) {

    $user_id = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);

    send_response(
        true,
        "Registration successful.",
        [
            "user_id" => $user_id,
            "name" => $name,
            "email" => $email
        ]
    );

} else {

    mysqli_stmt_close($stmt);

    send_response(false, "Registration failed.");
}