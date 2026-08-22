<?php

session_start();

require_once "../config/database.php";


// Admin check

if (
    !isset($_SESSION["user_id"]) ||
    $_SESSION["user_role"] !== "admin"
) {

    header("Location: ../login.php");

    exit;
}


// Get ID

$id = isset($_GET["id"])
    ? (int) $_GET["id"]
    : 0;


if ($id <= 0) {

    header("Location: menu.php");

    exit;
}


// Get image

$stmt = $conn->prepare(
    "SELECT image
     FROM menu_items
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 1) {

    $item = $result->fetch_assoc();

    $image = $item["image"];


    // Delete database record

    $delete = $conn->prepare(
        "DELETE FROM menu_items
         WHERE id = ?"
    );

    $delete->bind_param("i", $id);

    $delete->execute();


    // Delete image

    if (!empty($image)) {

        $imagePath =
            "../assets/images/" . $image;


        if (file_exists($imagePath)) {

            unlink($imagePath);

        }

    }

}


// Back to menu

header("Location: menu.php");

exit;

?>