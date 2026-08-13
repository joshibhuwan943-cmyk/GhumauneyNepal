<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../includes/functions.php";

/* Only admin can update destinations */
require_admin();

$id = (int) get_value("id");

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

if ($id <= 0) {
    send_response(false, "Valid destination ID is required.");
}

if ($name === "" || $province === "" || $category === "") {
    send_response(
        false,
        "Name, province and category are required."
    );
}

if (!is_numeric($budget) || $budget < 0) {
    send_response(false, "Invalid budget.");
}

if (!is_numeric($duration) || $duration <= 0) {
    send_response(false, "Invalid duration.");
}

$budget = (float) $budget;
$duration = (int) $duration;

$stmt = mysqli_prepare(
    $conn,
    "UPDATE destinations
     SET
        name = ?,
        province = ?,
        category = ?,
        budget = ?,
        duration = ?,
        best_time = ?,
        location_note = ?,
        description = ?,
        highlights = ?,
        image = ?
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "sssdiissssi",
    $name,
    $province,
    $category,
    $budget,
    $duration,
    $best_time,
    $location_note,
    $description,
    $highlights,
    $image,
    $id
);

if (!mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    send_response(
        false,
        "Failed to update destination."
    );
}

mysqli_stmt_close($stmt);

send_response(
    true,
    "Destination updated successfully.",
    [
        "id" => $id
    ]
);