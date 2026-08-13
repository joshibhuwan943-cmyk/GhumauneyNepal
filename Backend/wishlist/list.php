<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

$user_id = require_login();

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        w.id AS wishlist_id,
        d.id AS destination_id,
        d.name,
        d.province,
        d.category,
        d.budget,
        d.duration,
        d.best_time,
        d.location_note,
        d.description,
        d.highlights,
        d.image

     FROM wishlist w

     INNER JOIN destinations d
     ON w.destination_id = d.id

     WHERE w.user_id = ?

     ORDER BY w.created_at DESC"
);

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$wishlist = [];

while ($row = mysqli_fetch_assoc($result)) {

    $wishlist[] = [
        "wishlist_id" => (int) $row["wishlist_id"],
        "destination_id" => (int) $row["destination_id"],
        "name" => $row["name"],
        "province" => $row["province"],
        "category" => $row["category"],
        "budget" => (float) $row["budget"],
        "duration" => (int) $row["duration"],
        "best_time" => $row["best_time"],
        "location_note" => $row["location_note"],
        "description" => $row["description"],
        "highlights" => $row["highlights"],
        "image" => $row["image"]
    ];
}

mysqli_stmt_close($stmt);

send_response(
    true,
    "Wishlist loaded successfully.",
    $wishlist
);