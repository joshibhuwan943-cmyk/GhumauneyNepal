<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

$user_id = require_login();

$trip_name = get_value("trip_name");
$destination_id = (int) get_value("destination_id");
$budget = get_value("budget");
$travelers = (int) get_value("travelers");
$days = (int) get_value("days");
$travel_type = get_value("travel_type");
$interests = get_value("interests");

/* Validate required fields */

if ($trip_name === "") {
    send_response(false, "Trip name is required.");
}

if ($destination_id <= 0) {
    send_response(false, "Valid destination is required.");
}

if (!is_numeric($budget) || $budget < 0) {
    send_response(false, "Invalid budget.");
}

if ($travelers <= 0) {
    send_response(false, "Number of travelers must be greater than 0.");
}

if ($days <= 0) {
    send_response(false, "Number of days must be greater than 0.");
}

$budget = (float) $budget;

/* Check destination */

$stmt = mysqli_prepare(
    $conn,
    "SELECT id FROM destinations
     WHERE id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $destination_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$destination = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$destination) {
    send_response(false, "Destination not found.");
}

/* Create trip */

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO trip_plans
    (
        user_id,
        trip_name,
        destination_id,
        budget,
        travelers,
        days,
        travel_type,
        interests
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "isidiiss",
    $user_id,
    $trip_name,
    $destination_id,
    $budget,
    $travelers,
    $days,
    $travel_type,
    $interests
);

if (!mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    send_response(
        false,
        "Failed to create trip plan."
    );
}

$trip_id = mysqli_insert_id($conn);

mysqli_stmt_close($stmt);

send_response(
    true,
    "Trip plan created successfully.",
    [
        "trip_id" => $trip_id
    ]
);