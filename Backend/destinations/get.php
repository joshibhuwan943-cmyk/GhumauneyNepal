<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

$id = (int) get_value("id");

if ($id <= 0) {
    send_response(false, "Valid destination ID is required.");
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        id,
        name,
        province,
        category,
        budget,
        duration,
        best_time,
        location_note,
        description,
        highlights,
        image
     FROM destinations
     WHERE id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$destination = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$destination) {
    send_response(false, "Destination not found.");
}

$destination["id"] = (int) $destination["id"];
$destination["budget"] = (float) $destination["budget"];
$destination["duration"] = (int) $destination["duration"];

send_response(
    true,
    "Destination loaded successfully.",
    $destination
);