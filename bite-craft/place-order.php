<?php

session_start();

require_once "config/database.php";


// ==================================================
// LOGIN CHECK
// ==================================================

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}


// ==================================================
// CART CHECK
// ==================================================

if (
    !isset($_SESSION["cart"]) ||
    empty($_SESSION["cart"])
) {

    header("Location: menu.php");

    exit;
}


// ==================================================
// USER DATA
// ==================================================

$user_id = (int) $_SESSION["user_id"];

$user_name = "";


// Get logged-in user

$stmt = $conn->prepare(
    "SELECT name
     FROM users
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

    $user_name = $user["name"];

}


$stmt->close();


// ==================================================
// CART
// ==================================================

$cart = $_SESSION["cart"];


// ==================================================
// CALCULATE TOTAL
// ==================================================

$total = 0;


foreach ($cart as $item) {

    $price =
        (float) $item["price"];

    $quantity =
        (int) $item["quantity"];

    $total +=
        $price * $quantity;
}


// ==================================================
// GET CUSTOMER DATA
// ==================================================

$phone =
    trim($_POST["phone"] ?? "");

$address =
    trim($_POST["address"] ?? "");


// ==================================================
// VALIDATION
// ==================================================

if (
    empty($phone) ||
    empty($address)
) {

    die("Please fill all required fields.");

}


if (strlen($phone) < 9) {

    die("Please enter a valid phone number.");

}


if (strlen($address) < 10) {

    die("Please enter a complete delivery address.");

}


// ==================================================
// DATABASE TRANSACTION
// ==================================================

$conn->begin_transaction();


try {


    // ==================================================
    // INSERT ORDER
    // ==================================================

    $order_stmt = $conn->prepare(

        "INSERT INTO orders
        (
            customer_name,
            phone,
            address,
            total_amount,
            status
        )
        VALUES
        (?, ?, ?, ?, 'pending')"

    );


    $order_stmt->bind_param(

        "sssd",

        $user_name,

        $phone,

        $address,

        $total

    );


    if (!$order_stmt->execute()) {

        throw new Exception(
            "Failed to create order."
        );

    }


    // ==================================================
    // GET ORDER ID
    // ==================================================

    $order_id =
        $conn->insert_id;


    $order_stmt->close();


    // ==================================================
    // INSERT ORDER ITEMS
    // ==================================================

    $item_stmt = $conn->prepare(

        "INSERT INTO order_items
        (
            order_id,
            product_name,
            price,
            quantity,
            subtotal
        )
        VALUES
        (?, ?, ?, ?, ?)"

    );


    foreach ($cart as $item) {


        // Product name

        $product_name =
            $item["name"];


        // Price

        $price =
            (float) $item["price"];


        // Quantity

        $quantity =
            (int) $item["quantity"];


        // Subtotal

        $subtotal =
            $price * $quantity;


        // Insert

        $item_stmt->bind_param(

            "isdid",

            $order_id,

            $product_name,

            $price,

            $quantity,

            $subtotal

        );


        if (!$item_stmt->execute()) {

            throw new Exception(
                "Failed to save order item."
            );

        }

    }


    $item_stmt->close();


    // ==================================================
    // COMMIT
    // ==================================================

    $conn->commit();


    // ==================================================
    // CLEAR CART
    // ==================================================

    unset(
        $_SESSION["cart"]
    );


    // ==================================================
    // SAVE LAST ORDER ID
    // ==================================================

    $_SESSION["last_order_id"] =
        $order_id;


    // ==================================================
    // REDIRECT TO SUCCESS PAGE
    // ==================================================

    header(
        "Location: order-success.php"
    );

    exit;


} catch (Exception $e) {


    // ==================================================
    // ROLLBACK
    // ==================================================

    $conn->rollback();


    echo "

    <!DOCTYPE html>

    <html lang='en'>

    <head>

        <meta charset='UTF-8'>

        <meta
            name='viewport'
            content='width=device-width, initial-scale=1.0'>

        <title>Order Error | BiteCraft</title>


        <link
            href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'
            rel='stylesheet'>

    </head>


    <body class='bg-light'>


        <div class='container py-5'>

            <div
                class='alert alert-danger shadow-sm'>


                <h4 class='fw-bold'>

                    Order Failed

                </h4>


                <p>

                    Something went wrong while placing your order.

                </p>


                <p class='small text-secondary'>

                    Please try again.

                </p>


                <a
                    href='checkout.php'
                    class='btn btn-dark'>

                    Back to Checkout

                </a>


            </div>

        </div>


    </body>

    </html>

    ";

}

?>