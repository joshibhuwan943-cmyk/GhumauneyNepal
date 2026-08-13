<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

/* Make sure the user is logged in */
$user_id = require_login();

/* Get trip ID */
$trip_id = (int) get_value("id");

/* Validate trip ID */
if ($trip_id <= 0) {
    send_response(false, "Valid trip ID is required.");
}

/* Delete only the logged-in user's trip */
$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM trip_plans
     WHERE id = ?
     AND user_id = ?"
);

if (!$stmt) {
    send_response(false, "Database query preparation failed.");
}

/* Bind parameters */
mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $trip_id,
    $user_id
);

/* Execute delete */
if (!mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    send_response(
        false,
        "Failed to delete trip plan."
    );
}

/* Check whether a trip was actually deleted */
if (mysqli_stmt_affected_rows($stmt) === 0) {

    mysqli_stmt_close($stmt);

    send_response(
        false,
        "Trip plan not found."
    );
}

mysqli_stmt_close($stmt);

/* Success */
send_response(
    true,
    "Trip plan deleted successfully.",
    [
        "trip_id" => $trip_id
    ]
);