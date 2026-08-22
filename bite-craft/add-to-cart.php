<?php

session_start();

require_once "config/database.php";


// ========================================
// GET PRODUCT ID
// ========================================

$product_id = isset($_GET["id"])
    ? (int) $_GET["id"]
    : 0;


if ($product_id <= 0) {

    header("Location: menu.php");

    exit;
}


// ========================================
// GET MENU ITEM
// ========================================

$stmt = $conn->prepare(
    "SELECT id, name, price, image
     FROM menu_items
     WHERE id = ?
     AND status = 'available'
     LIMIT 1"
);

$stmt->bind_param(
    "i",
    $product_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {

    header("Location: menu.php");

    exit;
}


$item = $result->fetch_assoc();


// ========================================
// CREATE CART
// ========================================

if (!isset($_SESSION["cart"])) {

    $_SESSION["cart"] = [];

}


// ========================================
// ADD / UPDATE QUANTITY
// ========================================

if (isset($_SESSION["cart"][$product_id])) {

    $_SESSION["cart"][$product_id]["quantity"]++;

} else {

    $_SESSION["cart"][$product_id] = [

        "id" => $item["id"],

        "name" => $item["name"],

        "price" => $item["price"],

        "image" => $item["image"],

        "quantity" => 1

    ];

}


// ========================================
// REDIRECT TO CART
// ========================================

header("Location: cart.php");

exit;

?>