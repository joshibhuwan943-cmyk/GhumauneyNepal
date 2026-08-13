<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

/* Make sure the user is logged in */
$user_id = require_login();

/* Get form/request values */
$trip_id = (int) get_value("id");
$trip_name = get_value("trip_name");
$destination_id = (int) get_value("destination_id");
$budget = (float) get_value("budget");
$travelers = (int) get_value("travelers");
$days = (int) get_value("days");
$travel_type = get_value("travel_type");
$interests = get_value("interests");


/* =========================
   VALIDATION
   ========================= */

if ($trip_id <= 0) {
    send_response(false, "Valid trip ID is required.");
}

if ($trip_name === "") {
    send_response(false, "Trip name is required.");
}

if ($destination_id <= 0) {
    send_response(false, "Valid destination ID is required.");
}

if ($budget < 0) {
    send_response(false, "Budget cannot be negative.");
}

if ($travelers <= 0) {
    send_response(
        false,
        "Number of travelers must be greater than 0."
    );
}

if ($days <= 0) {
    send_response(
        false,
        "Number of days must be greater than 0."
    );
}


/* =========================
   CHECK DESTINATION
   ========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT id
     FROM destinations
     WHERE id = ?
     LIMIT 1"
);

if (!$stmt) {
    send_response(false, "Database query preparation failed.");
}

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


/* =========================
   UPDATE TRIP
   ========================= */

/*
   The trip can only be updated if
   it belongs to the currently logged-in user.
*/

$stmt = mysqli_prepare(
    $conn,
    "UPDATE trip_plans
     SET
        trip_name = ?,
        destination_id = ?,
        budget = ?,
        travelers = ?,
        days = ?,
        travel_type = ?,
        interests = ?
     WHERE id = ?
     AND user_id = ?"
);

if (!$stmt) {
    send_response(false, "Database query preparation failed.");
}


/*
   Parameter types:

   s = trip_name
   i = destination_id
   d = budget
   i = travelers
   i = days
   s = travel_type
   s = interests
   i = trip_id
   i = user_id
*/

mysqli_stmt_bind_param(
    $stmt,
    "sidiissii",
    $trip_name,
    $destination_id,
    $budget,
    $travelers,
    $days,
    $travel_type,
    $interests,
    $trip_id,
    $user_id
);


/* Execute update */

if (!mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    send_response(
        false,
        "Failed to update trip plan."
    );
}


/* Check whether the trip existed */

if (mysqli_stmt_affected_rows($stmt) === 0) {

    mysqli_stmt_close($stmt);

    send_response(
        false,
        "Trip plan not found or no changes were made."
    );
}

mysqli_stmt_close($stmt);


/* =========================
   SUCCESS RESPONSE
   ========================= */

send_response(
    true,
    "Trip plan updated successfully.",
    [
        "trip_id" => $trip_id
    ]
);