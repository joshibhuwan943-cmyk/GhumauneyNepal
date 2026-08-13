<?php

require_once __DIR__ . "/../includes/functions.php";

if (isset($_SESSION["user_id"])) {

    send_response(
        true,
        "User is logged in.",
        [
            "logged_in" => true,
            "user_id" => (int) $_SESSION["user_id"],
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
            "role" => $_SESSION["role"]
        ]
    );
}

send_response(
    true,
    "User is not logged in.",
    [
        "logged_in" => false
    ]
);