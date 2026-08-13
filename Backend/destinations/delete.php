<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

/* Only admin can delete destinations */
require_admin();

$id = (int) get_value("id");

if ($id <= 0) {
    send_response(false, "Valid destination ID is required.");
}

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM destinations
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (!mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    send_response(
        false,
        "Failed to delete destination."
    );
}

if (mysqli_stmt_affected_rows($stmt) === 0) {

    mysqli_stmt_close($stmt);

    send_response(
        false,
        "Destination not found."
    );
}

mysqli_stmt_close($stmt);

send_response(
    true,
    "Destination deleted successfully."
);