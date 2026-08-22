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
$user_email = "";


// Get logged-in user

$stmt = $conn->prepare(
    "SELECT name, email
     FROM users
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$user_result = $stmt->get_result();


if ($user_result->num_rows === 1) {

    $user = $user_result->fetch_assoc();

    $user_name = $user["name"];

    $user_email = $user["email"];

}


// ==================================================
// CART
// ==================================================

$cart = $_SESSION["cart"];


// Calculate total

$total = 0;

foreach ($cart as $item) {

    $total +=
        $item["price"]
        *
        $item["quantity"];

}


// ==================================================
// FORM VALUES
// ==================================================

$phone = "";

$address = "";

$error = "";


// ==================================================
// PLACE ORDER
// ==================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $phone = trim(
        $_POST["phone"] ?? ""
    );


    $address = trim(
        $_POST["address"] ?? ""
    );


    // ==================================================
    // VALIDATION
    // ==================================================

    if (
        empty($phone) ||
        empty($address)
    ) {

        $error =
            "Please fill all required fields.";

    }


    elseif (strlen($phone) < 9) {

        $error =
            "Please enter a valid phone number.";

    }


    elseif (strlen($address) < 10) {

        $error =
            "Please enter a complete delivery address.";

    }


    else {


        // ==================================================
        // START DATABASE TRANSACTION
        // ==================================================

        $conn->begin_transaction();


        try {


            // ==================================================
            // INSERT ORDER
            // ==================================================

            $order_stmt = $conn->prepare(
                "INSERT INTO orders
                (
                    user_id,
                    customer_name,
                    phone,
                    address,
                    total_amount,
                    status
                )
                VALUES (?, ?, ?, ?, ?, 'pending')"
            );


            $order_stmt->bind_param(
                "isssd",
                $user_id,
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


            // Get Order ID

            $order_id = $conn->insert_id;


            // ==================================================
            // INSERT ORDER ITEMS
            // ==================================================

            $item_stmt = $conn->prepare(
                "INSERT INTO order_items
                (
                    order_id,
                    product_id,
                    product_name,
                    price,
                    quantity,
                    subtotal
                )
                VALUES (?, ?, ?, ?, ?, ?)"
            );


            foreach ($cart as $item) {


                $product_id =
                    (int) $item["id"];


                $product_name =
                    $item["name"];


                $price =
                    (float) $item["price"];


                $quantity =
                    (int) $item["quantity"];


                $subtotal =
                    $price * $quantity;


                $item_stmt->bind_param(
                    "iisdid",
                    $order_id,
                    $product_id,
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


            // ==================================================
            // COMMIT
            // ==================================================

            $conn->commit();


            // Empty cart

            unset(
                $_SESSION["cart"]
            );


            // Save order ID

            $_SESSION["last_order_id"] =
                $order_id;


            // Redirect

            header(
                "Location: order-success.php"
            );

            exit;


        } catch (Exception $e) {


            // Rollback

            $conn->rollback();


            $error =
                $e->getMessage();

        }

    }

}

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Checkout | BiteCraft</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>

        body {

            background: #f8f9fa;

        }


        .checkout-card {

            border: none;

            border-radius: 15px;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.08);

        }


        .order-item {

            border-bottom: 1px solid #eee;

            padding: 15px 0;

        }


        .order-item:last-child {

            border-bottom: none;

        }


        .price {

            color: #dc9f00;

            font-weight: bold;

        }

    </style>

</head>


<body>


<!-- ==================================================
     NAVBAR
================================================== -->

<nav class="navbar navbar-dark bg-dark">

    <div class="container">


        <a
            href="index.php"
            class="navbar-brand fw-bold">

            <i
                class="bi bi-egg-fried text-warning">
            </i>

            Bite<span class="text-warning">

                Craft

            </span>

        </a>


        <a
            href="cart.php"
            class="btn btn-outline-light btn-sm">

            <i class="bi bi-cart3"></i>

            Back to Cart

        </a>


    </div>

</nav>



<!-- ==================================================
     CHECKOUT
================================================== -->

<div class="container py-5">


    <!-- PAGE TITLE -->

    <div class="mb-4">

        <h2 class="fw-bold">

            Checkout

        </h2>

        <p class="text-secondary">

            Complete your order details.

        </p>

    </div>



    <!-- ERROR -->

    <?php if (!empty($error)): ?>

        <div class="alert alert-danger">

            <i
                class="bi bi-exclamation-circle-fill me-2">
            </i>

            <?php echo htmlspecialchars($error); ?>

        </div>

    <?php endif; ?>



    <div class="row g-4">


        <!-- ==================================================
             CUSTOMER DETAILS
        =================================================== -->

        <div class="col-lg-7">


            <div class="card checkout-card">


                <div class="card-body p-4">


                    <h4 class="fw-bold mb-4">

                        <i
                            class="bi bi-person text-warning">
                        </i>

                        Delivery Details

                    </h4>



                    <form
                        method="POST">


                        <!-- NAME -->

                        <div class="mb-3">


                            <label
                                class="form-label fw-semibold">

                                Full Name

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                value="<?php echo htmlspecialchars($user_name); ?>"
                                readonly>


                        </div>



                        <!-- EMAIL -->

                        <div class="mb-3">


                            <label
                                class="form-label fw-semibold">

                                Email

                            </label>


                            <input
                                type="email"
                                class="form-control"
                                value="<?php echo htmlspecialchars($user_email); ?>"
                                readonly>


                        </div>



                        <!-- PHONE -->

                        <div class="mb-3">


                            <label
                                class="form-label fw-semibold">

                                Phone Number

                                <span
                                    class="text-danger">

                                    *

                                </span>

                            </label>


                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                placeholder="0771234567"
                                value="<?php echo htmlspecialchars($phone); ?>"
                                required>


                        </div>



                        <!-- ADDRESS -->

                        <div class="mb-4">


                            <label
                                class="form-label fw-semibold">

                                Delivery Address

                                <span
                                    class="text-danger">

                                    *

                                </span>

                            </label>


                            <textarea
                                name="address"
                                class="form-control"
                                rows="4"
                                placeholder="Enter your complete delivery address"
                                required><?php

                                echo htmlspecialchars(
                                    $address
                                );

                                ?></textarea>


                        </div>



                        <!-- PAYMENT -->

                        <div class="mb-4">


                            <label
                                class="form-label fw-semibold">

                                Payment Method

                            </label>


                            <div
                                class="border rounded p-3 bg-light">


                                <i
                                    class="bi bi-cash-stack text-success me-2">
                                </i>


                                <strong>

                                    Cash on Delivery

                                </strong>


                                <br>


                                <small
                                    class="text-secondary ms-4">

                                    Pay when your order is delivered.

                                </small>


                            </div>


                        </div>



                        <!-- SUBMIT -->

                        <button
                            type="submit"
                            class="btn btn-warning w-100 py-3 fw-bold">


                            <i
                                class="bi bi-check-circle">
                            </i>


                            Place Order


                        </button>


                    </form>


                </div>


            </div>


        </div>



        <!-- ==================================================
             ORDER SUMMARY
        =================================================== -->

        <div class="col-lg-5">


            <div class="card checkout-card">


                <div class="card-body p-4">


                    <h4 class="fw-bold mb-3">

                        <i
                            class="bi bi-receipt text-warning">
                        </i>

                        Your Order

                    </h4>



                    <!-- ITEMS -->


                    <?php foreach ($cart as $item): ?>


                        <?php

                        $subtotal =
                            $item["price"]
                            *
                            $item["quantity"];

                        ?>


                        <div
                            class="order-item">


                            <div
                                class="d-flex justify-content-between">


                                <div>


                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $item["name"]
                                        );

                                        ?>

                                    </strong>


                                    <br>


                                    <small
                                        class="text-secondary">

                                        Rs.
                                        <?php

                                        echo number_format(
                                            $item["price"],
                                            2
                                        );

                                        ?>

                                        ×

                                        <?php

                                        echo $item["quantity"];

                                        ?>

                                    </small>


                                </div>


                                <strong
                                    class="price">


                                    Rs.
                                    <?php

                                    echo number_format(
                                        $subtotal,
                                        2
                                    );

                                    ?>


                                </strong>


                            </div>


                        </div>


                    <?php endforeach; ?>



                    <!-- TOTAL -->

                    <div class="mt-3">


                        <div
                            class="d-flex justify-content-between mb-2">


                            <span>

                                Subtotal

                            </span>


                            <strong>

                                Rs.
                                <?php

                                echo number_format(
                                    $total,
                                    2
                                );

                                ?>

                            </strong>


                        </div>


                        <div
                            class="d-flex justify-content-between mb-3">


                            <span>

                                Delivery Fee

                            </span>


                            <strong
                                class="text-success">

                                FREE

                            </strong>


                        </div>


                        <hr>


                        <div
                            class="d-flex justify-content-between">


                            <h5 class="fw-bold">

                                Total

                            </h5>


                            <h5
                                class="fw-bold text-warning">


                                Rs.
                                <?php

                                echo number_format(
                                    $total,
                                    2
                                );

                                ?>


                            </h5>


                        </div>


                    </div>


                </div>


            </div>


        </div>


    </div>


</div>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>