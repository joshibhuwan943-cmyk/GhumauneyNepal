<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

/* Only admin can create destinations */
require_admin();

$name = get_value("name");
$province = get_value("province");
$category = get_value("category");
$budget = get_value("budget");
$duration = get_value("duration");
$best_time = get_value("best_time");
$location_note = get_value("location_note");
$description = get_value("description");
$highlights = get_value("highlights");
$image = get_value("image");

/* Required fields */
if ($name === "" || $province === "" || $category === "") {
    send_response(
        false,
        "Name, province and category are required."
    );
}

/* Validate budget */
if (!is_numeric($budget) || $budget < 0) {
    send_response(false, "Invalid budget.");
}

/* Validate duration */
if (!is_numeric($duration) || $duration <= 0) {
    send_response(false, "Invalid duration.");
}

$budget = (float) $budget;
$duration = (int) $duration;

/* Insert destination */
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO destinations
    (
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
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "sssdiissss",
    $name,
    $province,
    $category,
    $budget,
    $duration,
    $best_time,
    $location_note,
    $description,
    $highlights,
    $image
);

if (!mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    send_response(
        false,
        "Failed to create destination."
    );
}

$destination_id = mysqli_insert_id($conn);

mysqli_stmt_close($stmt);

send_response(
    true,
    "Destination created successfully.",
    [
        "id" => $destination_id
    ]
);