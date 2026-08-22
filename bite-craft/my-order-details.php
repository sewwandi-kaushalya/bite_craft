<?php

session_start();

require_once "config/database.php";

// ========================================
// LOGIN CHECK
// ========================================

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit;
}

// ========================================
// GET USER ID
// ========================================

$user_id = (int) $_SESSION["user_id"];

// ========================================
// GET ORDER ID
// ========================================

$order_id = isset($_GET["id"])
    ? (int) $_GET["id"]
    : 0;

if ($order_id <= 0) {

    header("Location: my-orders.php");
    exit;
}

// ========================================
// GET ORDER
// IMPORTANT:
// user_id = ?
// This prevents one customer from viewing
// another customer's order.
// ========================================

$stmt = $conn->prepare(
    "SELECT id,
            customer_name,
            phone,
            address,
            total_amount,
            status,
            created_at
     FROM orders
     WHERE id = ?
     AND user_id = ?
     LIMIT 1"
);

$stmt->bind_param(
    "ii",
    $order_id,
    $user_id
);

$stmt->execute();

$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {

    $stmt->close();

    header("Location: my-orders.php");
    exit;
}

$order = $order_result->fetch_assoc();

$stmt->close();

// ========================================
// GET ORDER ITEMS
// ========================================

$item_stmt = $conn->prepare(
    "SELECT product_name,
            price,
            quantity,
            subtotal
     FROM order_items
     WHERE order_id = ?
     ORDER BY id ASC"
);

$item_stmt->bind_param(
    "i",
    $order_id
);

$item_stmt->execute();

$items = $item_stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Order #<?php echo $order_id; ?> | BiteCraft
    </title>

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
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-size: 24px;
        }

        .detail-card {

            background-color: white;

            border: none;

            border-radius: 15px;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.06);
        }

        .price {

            color: #dc9f00;

            font-weight: bold;
        }

        .item-row {

            border-bottom: 1px solid #eee;

            padding: 18px 0;
        }

        .item-row:last-child {

            border-bottom: none;
        }

        .status-box {

            border-radius: 12px;

            padding: 15px;

            background-color: #f8f9fa;
        }

        .order-id {

            font-size: 22px;

            font-weight: bold;
        }

    </style>

</head>

<body>


<!-- ========================================
     NAVBAR
======================================== -->

<nav class="navbar navbar-dark bg-dark">

    <div class="container">


        <!-- LOGO -->

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


        <!-- BACK -->

        <a
            href="my-orders.php"
            class="btn btn-outline-light btn-sm">

            <i class="bi bi-arrow-left"></i>

            My Orders

        </a>


    </div>

</nav>


<!-- ========================================
     CONTENT
======================================== -->

<div class="container py-5">


    <!-- PAGE HEADER -->

    <div
        class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">

                Order Details

            </h2>

            <p class="text-secondary mb-0">

                Order #<?php echo $order_id; ?>

            </p>

        </div>


        <a
            href="my-orders.php"
            class="btn btn-outline-dark">

            <i class="bi bi-arrow-left"></i>

            Back

        </a>

    </div>


    <div class="row g-4">


        <!-- ====================================
             ORDER INFORMATION
        ===================================== -->

        <div class="col-lg-5">


            <!-- CUSTOMER DETAILS -->

            <div
                class="card detail-card p-4 mb-4">


                <h4 class="fw-bold mb-4">

                    <i
                        class="bi bi-person text-warning">
                    </i>

                    Delivery Details

                </h4>


                <!-- NAME -->

                <div class="mb-3">

                    <small
                        class="text-secondary">

                        Customer Name

                    </small>

                    <div class="fw-semibold">

                        <?php

                        echo htmlspecialchars(
                            $order[
                                "customer_name"
                            ]
                        );

                        ?>

                    </div>

                </div>


                <!-- PHONE -->

                <div class="mb-3">

                    <small
                        class="text-secondary">

                        Phone Number

                    </small>

                    <div class="fw-semibold">

                        <?php

                        echo htmlspecialchars(
                            $order["phone"]
                        );

                        ?>

                    </div>

                </div>


                <!-- ADDRESS -->

                <div class="mb-3">

                    <small
                        class="text-secondary">

                        Delivery Address

                    </small>

                    <div class="fw-semibold">

                        <?php

                        echo nl2br(
                            htmlspecialchars(
                                $order["address"]
                            )
                        );

                        ?>

                    </div>

                </div>


                <!-- DATE -->

                <div>

                    <small
                        class="text-secondary">

                        Order Date

                    </small>

                    <div class="fw-semibold">

                        <?php

                        echo date(
                            "d M Y, h:i A",
                            strtotime(
                                $order[
                                    "created_at"
                                ]
                            )
                        );

                        ?>

                    </div>

                </div>


            </div>


            <!-- ====================================
                 ORDER STATUS
            ===================================== -->

            <div
                class="card detail-card p-4">


                <h4 class="fw-bold mb-3">

                    <i
                        class="bi bi-clock-history text-warning">
                    </i>

                    Order Status

                </h4>


                <?php

                $status =
                    $order["status"];

                $badge =
                    "secondary";

                switch ($status) {

                    case "pending":

                        $badge =
                            "warning";

                        break;

                    case "confirmed":

                        $badge =
                            "primary";

                        break;

                    case "preparing":

                        $badge =
                            "info";

                        break;

                    case "ready":

                        $badge =
                            "success";

                        break;

                    case "delivered":

                        $badge =
                            "dark";

                        break;

                    case "cancelled":

                        $badge =
                            "danger";

                        break;

                }

                ?>


                <div class="status-box">

                    <span
                        class="badge bg-<?php echo $badge; ?> fs-6">

                        <?php

                        echo ucfirst(
                            $status
                        );

                        ?>

                    </span>


                    <?php if (
                        $status === "pending"
                    ): ?>

                        <p
                            class="text-secondary mt-3 mb-0">

                            Your order has been received
                            and is waiting for confirmation.

                        </p>

                    <?php elseif (
                        $status === "confirmed"
                    ): ?>

                        <p
                            class="text-secondary mt-3 mb-0">

                            Your order has been confirmed.

                        </p>

                    <?php elseif (
                        $status === "preparing"
                    ): ?>

                        <p
                            class="text-secondary mt-3 mb-0">

                            Your food is currently being prepared.

                        </p>

                    <?php elseif (
                        $status === "ready"
                    ): ?>

                        <p
                            class="text-secondary mt-3 mb-0">

                            Your order is ready.

                        </p>

                    <?php elseif (
                        $status === "delivered"
                    ): ?>

                        <p
                            class="text-secondary mt-3 mb-0">

                            Your order has been delivered.
                            Thank you for ordering from BiteCraft!

                        </p>

                    <?php elseif (
                        $status === "cancelled"
                    ): ?>

                        <p
                            class="text-secondary mt-3 mb-0">

                            This order has been cancelled.

                        </p>

                    <?php endif; ?>

                </div>


            </div>


        </div>


        <!-- ====================================
             ORDER ITEMS
        ===================================== -->

        <div class="col-lg-7">


            <div
                class="card detail-card p-4">


                <h4 class="fw-bold mb-4">

                    <i
                        class="bi bi-bag text-warning">
                    </i>

                    Ordered Items

                </h4>


                <?php if (
                    $items &&
                    $items->num_rows > 0
                ): ?>


                    <?php while (
                        $item =
                        $items->fetch_assoc()
                    ): ?>


                        <div
                            class="item-row">


                            <div
                                class="d-flex justify-content-between align-items-center">


                                <!-- ITEM -->

                                <div>

                                    <h6
                                        class="fw-bold mb-1">

                                        <?php

                                        echo htmlspecialchars(
                                            $item[
                                                "product_name"
                                            ]
                                        );

                                        ?>

                                    </h6>


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

                                        echo (int)
                                            $item[
                                                "quantity"
                                            ];

                                        ?>

                                    </small>

                                </div>


                                <!-- SUBTOTAL -->

                                <strong
                                    class="price">

                                    Rs.

                                    <?php

                                    echo number_format(
                                        $item[
                                            "subtotal"
                                        ],
                                        2
                                    );

                                    ?>

                                </strong>


                            </div>


                        </div>


                    <?php endwhile; ?>


                <?php else: ?>


                    <div
                        class="text-center py-4">

                        <i
                            class="bi bi-bag-x"
                            style="font-size:50px;">
                        </i>

                        <p
                            class="text-secondary mt-3">

                            No order items found.

                        </p>

                    </div>


                <?php endif; ?>


                <!-- ====================================
                     TOTAL
                ===================================== -->

                <hr>


                <div
                    class="d-flex justify-content-between align-items-center mt-3">

                    <h4 class="fw-bold">

                        Total

                    </h4>


                    <h4
                        class="fw-bold price">

                        Rs.

                        <?php

                        echo number_format(
                            $order[
                                "total_amount"
                            ],
                            2
                        );

                        ?>

                    </h4>

                </div>


                <!-- PAYMENT -->

                <div
                    class="alert alert-light border mt-4 mb-0">

                    <i
                        class="bi bi-cash-stack text-success me-2">
                    </i>

                    <strong>

                        Payment Method:

                    </strong>

                    Cash on Delivery

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