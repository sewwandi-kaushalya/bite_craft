<?php

session_start();

require_once "../config/database.php";

// ========================================
// CHECK LOGIN
// ========================================

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit;
}

// ========================================
// CHECK ADMIN
// ========================================

if ($_SESSION["user_role"] !== "admin") {

    header("Location: ../index.php");
    exit;
}

// ========================================
// GET ORDER ID
// ========================================

$order_id = isset($_GET["id"])
    ? (int) $_GET["id"]
    : 0;

if ($order_id <= 0) {

    header("Location: orders.php");
    exit;
}

// ========================================
// UPDATE ORDER STATUS
// ========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $status = $_POST["status"] ?? "";

    $allowed_statuses = [
        "pending",
        "confirmed",
        "preparing",
        "ready",
        "delivered",
        "cancelled"
    ];

    if (in_array($status, $allowed_statuses, true)) {

        $update = $conn->prepare(
            "UPDATE orders
             SET status = ?
             WHERE id = ?"
        );

        $update->bind_param(
            "si",
            $status,
            $order_id
        );

        $update->execute();

        $update->close();
    }

    header("Location: view-order.php?id=" . $order_id);
    exit;
}

// ========================================
// GET ORDER
// ========================================

$stmt = $conn->prepare(
    "SELECT *
     FROM orders
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param(
    "i",
    $order_id
);

$stmt->execute();

$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {

    $stmt->close();

    header("Location: orders.php");
    exit;
}

$order = $order_result->fetch_assoc();

$stmt->close();

// ========================================
// GET ORDER ITEMS
// ========================================

$item_stmt = $conn->prepare(
    "SELECT *
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

        /* ================================
           SIDEBAR
        ================================= */

        .sidebar {

            width: 250px;

            min-height: 100vh;

            background-color: #212529;

            position: fixed;

            left: 0;

            top: 0;

            padding-top: 20px;
        }

        .sidebar-logo {

            color: white;

            font-size: 25px;

            font-weight: bold;

            text-decoration: none;

            display: block;

            padding: 15px 25px;

            margin-bottom: 20px;
        }

        .sidebar-logo span {
            color: #ffc107;
        }

        .sidebar-link {

            display: block;

            color: #adb5bd;

            text-decoration: none;

            padding: 13px 25px;

            transition: 0.3s;
        }

        .sidebar-link:hover {

            color: white;

            background-color: #343a40;
        }

        .sidebar-link.active {

            color: #212529;

            background-color: #ffc107;
        }

        .sidebar-link i {
            width: 25px;
        }

        /* ================================
           MAIN
        ================================= */

        .main-content {

            margin-left: 250px;

            min-height: 100vh;
        }

        /* ================================
           TOPBAR
        ================================= */

        .topbar {

            background-color: white;

            padding: 15px 30px;

            border-bottom: 1px solid #dee2e6;
        }

        /* ================================
           CARDS
        ================================= */

        .detail-card {

            background-color: white;

            border-radius: 15px;

            border: 0;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.05);
        }

        /* ================================
           MOBILE
        ================================= */

        @media (max-width: 768px) {

            .sidebar {

                width: 70px;
            }

            .sidebar-logo {

                font-size: 0;

                text-align: center;

                padding: 15px 5px;
            }

            .sidebar-logo i {

                font-size: 25px;
            }

            .sidebar-link {

                text-align: center;

                padding: 15px 5px;
            }

            .sidebar-link span {

                display: none;
            }

            .sidebar-link i {

                font-size: 20px;
            }

            .main-content {

                margin-left: 70px;
            }
        }

    </style>

</head>

<body>


<!-- ========================================
     SIDEBAR
======================================== -->

<aside class="sidebar">

    <!-- LOGO -->

    <a
        href="index.php"
        class="sidebar-logo">

        <i class="bi bi-egg-fried"></i>

        Bite<span>Craft</span>

    </a>


    <!-- DASHBOARD -->

    <a
        href="index.php"
        class="sidebar-link">

        <i class="bi bi-speedometer2"></i>

        <span>Dashboard</span>

    </a>


    <!-- MENU -->

    <a
        href="menu.php"
        class="sidebar-link">

        <i class="bi bi-egg-fried"></i>

        <span>Menu Items</span>

    </a>


    <!-- ADD MENU -->

    <a
        href="add-menu.php"
        class="sidebar-link">

        <i class="bi bi-plus-circle"></i>

        <span>Add Menu Item</span>

    </a>


    <!-- ORDERS -->

    <a
        href="orders.php"
        class="sidebar-link active">

        <i class="bi bi-receipt"></i>

        <span>Orders</span>

    </a>


    <!-- RESERVATIONS -->

    <a
        href="#"
        class="sidebar-link">

        <i class="bi bi-calendar-check"></i>

        <span>Reservations</span>

    </a>


    <!-- USERS -->

    <a
        href="#"
        class="sidebar-link">

        <i class="bi bi-people"></i>

        <span>Users</span>

    </a>


    <hr class="border-secondary mx-3">


    <!-- WEBSITE -->

    <a
        href="../index.php"
        class="sidebar-link">

        <i class="bi bi-globe"></i>

        <span>View Website</span>

    </a>


    <!-- LOGOUT -->

    <a
        href="logout.php"
        class="sidebar-link">

        <i class="bi bi-box-arrow-right"></i>

        <span>Logout</span>

    </a>

</aside>


<!-- ========================================
     MAIN CONTENT
======================================== -->

<main class="main-content">


    <!-- TOPBAR -->

    <div
        class="topbar d-flex justify-content-between align-items-center">

        <h5 class="mb-0 fw-bold">

            Order #<?php echo $order_id; ?>

        </h5>


        <div>

            <i class="bi bi-person-circle me-2"></i>

            <strong>

                <?php
                echo htmlspecialchars(
                    $_SESSION["user_name"]
                );
                ?>

            </strong>

        </div>

    </div>


    <!-- PAGE CONTENT -->

    <div class="container-fluid p-4">


        <!-- HEADER -->

        <div
            class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">

                    Order Details

                </h2>

                <p class="text-secondary">

                    View and manage customer order.

                </p>

            </div>


            <a
                href="orders.php"
                class="btn btn-outline-dark">

                <i class="bi bi-arrow-left"></i>

                Back to Orders

            </a>

        </div>


        <div class="row g-4">


            <!-- ====================================
                 CUSTOMER DETAILS
            ===================================== -->

            <div class="col-lg-5">


                <div class="detail-card p-4">


                    <h4 class="fw-bold mb-4">

                        <i
                            class="bi bi-person text-warning">
                        </i>

                        Customer Details

                    </h4>


                    <!-- NAME -->

                    <div class="mb-3">

                        <small class="text-secondary">

                            Customer Name

                        </small>

                        <div class="fw-semibold">

                            <?php

                            echo htmlspecialchars(
                                $order["customer_name"]
                            );

                            ?>

                        </div>

                    </div>


                    <!-- PHONE -->

                    <div class="mb-3">

                        <small class="text-secondary">

                            Phone

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

                        <small class="text-secondary">

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

                        <small class="text-secondary">

                            Order Date

                        </small>

                        <div class="fw-semibold">

                            <?php

                            echo date(
                                "d M Y, h:i A",
                                strtotime(
                                    $order["created_at"]
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
                    class="detail-card p-4 mt-4">


                    <h4 class="fw-bold mb-3">

                        <i
                            class="bi bi-arrow-repeat text-warning">
                        </i>

                        Order Status

                    </h4>


                    <form method="POST">


                        <select
                            name="status"
                            class="form-select mb-3"
                            required>


                            <option
                                value="pending"
                                <?php
                                echo $order["status"] === "pending"
                                    ? "selected"
                                    : "";
                                ?>>

                                Pending

                            </option>


                            <option
                                value="confirmed"
                                <?php
                                echo $order["status"] === "confirmed"
                                    ? "selected"
                                    : "";
                                ?>>

                                Confirmed

                            </option>


                            <option
                                value="preparing"
                                <?php
                                echo $order["status"] === "preparing"
                                    ? "selected"
                                    : "";
                                ?>>

                                Preparing

                            </option>


                            <option
                                value="ready"
                                <?php
                                echo $order["status"] === "ready"
                                    ? "selected"
                                    : "";
                                ?>>

                                Ready

                            </option>


                            <option
                                value="delivered"
                                <?php
                                echo $order["status"] === "delivered"
                                    ? "selected"
                                    : "";
                                ?>>

                                Delivered

                            </option>


                            <option
                                value="cancelled"
                                <?php
                                echo $order["status"] === "cancelled"
                                    ? "selected"
                                    : "";
                                ?>>

                                Cancelled

                            </option>


                        </select>


                        <button
                            type="submit"
                            class="btn btn-warning w-100">

                            <i class="bi bi-check-lg"></i>

                            Update Status

                        </button>


                    </form>


                </div>


            </div>


            <!-- ====================================
                 ORDER ITEMS
            ===================================== -->

            <div class="col-lg-7">


                <div class="detail-card p-4">


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
                                class="d-flex justify-content-between align-items-center border-bottom py-3">


                                <div>

                                    <h6
                                        class="fw-bold mb-1">

                                        <?php

                                        echo htmlspecialchars(
                                            $item["product_name"]
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
                                            $item["quantity"];

                                        ?>

                                    </small>

                                </div>


                                <strong
                                    class="text-warning">

                                    Rs.

                                    <?php

                                    echo number_format(
                                        $item["subtotal"],
                                        2
                                    );

                                    ?>

                                </strong>


                            </div>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <div
                            class="text-center py-4">

                            <i
                                class="bi bi-bag-x"
                                style="font-size:45px;">
                            </i>

                            <p
                                class="text-secondary mt-3">

                                No order items found.

                            </p>

                        </div>


                    <?php endif; ?>


                    <!-- TOTAL -->

                    <div
                        class="d-flex justify-content-between align-items-center mt-4">

                        <h4 class="fw-bold">

                            Total

                        </h4>


                        <h4
                            class="fw-bold text-warning">

                            Rs.

                            <?php

                            echo number_format(
                                $order["total_amount"],
                                2
                            );

                            ?>

                        </h4>

                    </div>


                </div>


            </div>


        </div>


    </div>


</main>


</body>

</html>