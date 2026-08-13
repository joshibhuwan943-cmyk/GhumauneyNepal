<?php
require_once __DIR__ . "/../includes/functions.php";
require_once __DIR__ . "/../config/db.php";
 
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    send_response(false, "Only POST requests are allowed.");
}
 
$full_name = clean_input(post_value("full_name"));
$email = strtolower(trim(post_value("email")));
$password = post_value("password");
$confirm_password = post_value("confirm_password");
 
// ---- Validation ----
if ($full_name === "" || $email === "" || $password === "" || $confirm_password === "") {
    send_response(false, "Please fill in every field.");
}
 
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_response(false, "Please enter a valid email address.");
}
 
if (strlen($password) < 6) {
    send_response(false, "Password must be at least 6 characters long.");
}
 
if ($password !== $confirm_password) {
    send_response(false, "Passwords do not match.");
}
 
// ---- Check for an existing account ----
$check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
mysqli_stmt_bind_param($check_stmt, "s", $email);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);
 
if (mysqli_stmt_num_rows($check_stmt) > 0) {
    mysqli_stmt_close($check_stmt);
    send_response(false, "An account with this email already exists.");
}
mysqli_stmt_close($check_stmt);
 
// ---- Create the account ----
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
 
$insert_stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($insert_stmt, "sss", $full_name, $email, $hashed_password);
 
if (!mysqli_stmt_execute($insert_stmt)) {
    mysqli_stmt_close($insert_stmt);
    send_response(false, "Could not create account: " . mysqli_error($conn));
}
 
$new_user_id = mysqli_insert_id($conn);
mysqli_stmt_close($insert_stmt);
 
// ---- Log the new user straight in ----
session_regenerate_id(true);
$_SESSION["user_id"] = $new_user_id;
$_SESSION["user_name"] = $full_name;
$_SESSION["user_email"] = $email;
 
send_response(true, "Account created successfully.", [
    "id" => $new_user_id,
    "full_name" => $full_name,
    "email" => $email
]);
