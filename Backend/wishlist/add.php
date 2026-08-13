<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

$user_id = require_login();

$destination_id = (int) get_value("destination_id");

if ($destination_id <= 0) {
    send_response(false, "Valid destination ID is required.");
}

/* Check destination exists */
$stmt = mysqli_prepare(
    $conn,
    "SELECT id FROM destinations WHERE id = ? LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "i", $destination_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$destination = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$destination) {
    send_response(false, "Destination not found.");
}

/* Add to wishlist */
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO wishlist (user_id, destination_id)
     VALUES (?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $user_id,
    $destination_id
);

if (!mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    send_response(
        false,
        "Destination is already in your wishlist or could not be saved."
    );
}

mysqli_stmt_close($stmt);

send_response(
    true,
    "Destination added to wishlist."
);