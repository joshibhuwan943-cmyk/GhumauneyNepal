<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Send JSON response */
function send_response($success, $message = "", $data = null)
{
    header("Content-Type: application/json");

    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ]);

    exit;
}

/* Get request value safely */
function get_value($key, $default = "")
{
    return isset($_REQUEST[$key]) ? trim($_REQUEST[$key]) : $default;
}

/* Check login */
function require_login()
{
    if (!isset($_SESSION["user_id"])) {
        send_response(false, "Login required.");
    }

    return (int) $_SESSION["user_id"];
}

/* Check admin */
function require_admin()
{
    if (!isset($_SESSION["user_id"])) {
        send_response(false, "Login required.");
    }

    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
        send_response(false, "Admin access required.");
    }

    return (int) $_SESSION["user_id"];
}