<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

$sql = "SELECT
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
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    send_response(false, "Could not load destinations.");
}

$destinations = [];

while ($row = mysqli_fetch_assoc($result)) {

    $destinations[] = [
        "id" => (int) $row["id"],
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

send_response(
    true,
    "Destinations loaded successfully.",
    $destinations
);