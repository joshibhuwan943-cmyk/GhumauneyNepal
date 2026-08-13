<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

$user_id = require_login();

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        t.id,
        t.trip_name,
        t.destination_id,
        d.name AS destination_name,
        d.province,
        t.budget,
        t.travelers,
        t.days,
        t.travel_type,
        t.interests,
        t.created_at,
        t.updated_at

     FROM trip_plans t

     INNER JOIN destinations d
     ON t.destination_id = d.id

     WHERE t.user_id = ?

     ORDER BY t.created_at DESC"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$trips = [];

while ($row = mysqli_fetch_assoc($result)) {

    $trips[] = [
        "id" => (int) $row["id"],
        "trip_name" => $row["trip_name"],
        "destination_id" => (int) $row["destination_id"],
        "destination_name" => $row["destination_name"],
        "province" => $row["province"],
        "budget" => (float) $row["budget"],
        "travelers" => (int) $row["travelers"],
        "days" => (int) $row["days"],
        "travel_type" => $row["travel_type"],
        "interests" => $row["interests"],
        "created_at" => $row["created_at"],
        "updated_at" => $row["updated_at"]
    ];
}

mysqli_stmt_close($stmt);

send_response(
    true,
    "Trip plans loaded successfully.",
    $trips
);