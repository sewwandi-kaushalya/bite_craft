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
// GET USER ORDERS
// ========================================

$stmt = $conn->prepare(
    "SELECT id, customer_name, phone, address,
            total_amount, status, created_at
     FROM orders
     WHERE user_id = ?
     ORDER BY id DESC"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$orders = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>My Orders | BiteCraft</title>

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

        .order-card {

            background: white;

            border: none;

            border-radius: 15px;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.06);

            transition: 0.3s;
        }

        .order-card:hover {

            transform: translateY(-3px);

            box-shadow:
                0 10px 25px
                rgba(0, 0, 0, 0.09);
        }

        .order-id {

            font-size: 20px;

            font-weight: bold;
        }

        .price {

            color: #dc9f00;

            font-weight: bold;
        }

        .empty-icon {

            font-size: 80px;

            color: #adb5bd;
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

            <i class="bi bi-egg-fried text-warning"></i>

            Bite<span class="text-warning">Craft</span>

        </a>


        <!-- NAVIGATION -->

        <div class="d-flex gap-2">

            <a
                href="menu.php"
                class="btn btn-outline-light btn-sm">

                <i class="bi bi-shop"></i>

                Menu

            </a>


            <a
                href="cart.php"
                class="btn btn-warning btn-sm">

                <i class="bi bi-cart3"></i>

                Cart

            </a>

        </div>

    </div>

</nav>


<!-- ========================================
     CONTENT
======================================== -->

<div class="container py-5">


    <!-- PAGE TITLE -->

    <div class="mb-4">

        <h2 class="fw-bold">

            <i class="bi bi-receipt text-warning"></i>

            My Orders

        </h2>

        <p class="text-secondary">

            View your order history and order status.

        </p>

    </div>


    <!-- ========================================
         ORDERS
    ======================================== -->

    <?php if ($orders && $orders->num_rows > 0): ?>


        <div class="row g-4">


            <?php while (
                $order = $orders->fetch_assoc()
            ): ?>


                <div class="col-md-6 col-lg-4">


                    <div class="card order-card h-100">


                        <div class="card-body p-4">


                            <!-- ORDER ID -->

                            <div
                                class="d-flex justify-content-between align-items-center mb-3">

                                <div>

                                    <small
                                        class="text-secondary">

                                        Order ID

                                    </small>

                                    <div class="order-id">

                                        #<?php

                                        echo (int)
                                            $order["id"];

                                        ?>

                                    </div>

                                </div>


                                <!-- STATUS -->

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


                                <span
                                    class="badge bg-<?php echo $badge; ?>">

                                    <?php

                                    echo ucfirst(
                                        $status
                                    );

                                    ?>

                                </span>

                            </div>


                            <hr>


                            <!-- DATE -->

                            <div class="mb-3">

                                <small
                                    class="text-secondary">

                                    <i
                                        class="bi bi-calendar3">
                                    </i>

                                    Order Date

                                </small>

                                <div>

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


                            <!-- CUSTOMER -->

                            <div class="mb-3">

                                <small
                                    class="text-secondary">

                                    <i
                                        class="bi bi-person">
                                    </i>

                                    Customer

                                </small>

                                <div>

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

                                    <i
                                        class="bi bi-telephone">
                                    </i>

                                    Phone

                                </small>

                                <div>

                                    <?php

                                    echo htmlspecialchars(
                                        $order["phone"]
                                    );

                                    ?>

                                </div>

                            </div>


                            <!-- TOTAL -->

                            <div
                                class="d-flex justify-content-between align-items-center mb-4">

                                <span
                                    class="fw-semibold">

                                    Total

                                </span>

                                <span
                                    class="price fs-5">

                                    Rs.

                                    <?php

                                    echo number_format(
                                        $order[
                                            "total_amount"
                                        ],
                                        2
                                    );

                                    ?>

                                </span>

                            </div>


                            <!-- VIEW ORDER -->

                            <a
                                href="my-order-details.php?id=<?php echo (int) $order["id"]; ?>"
                                class="btn btn-outline-dark w-100">

                                <i
                                    class="bi bi-eye">
                                </i>

                                View Order

                            </a>


                        </div>

                    </div>


                </div>


            <?php endwhile; ?>


        </div>


    <?php else: ?>


        <!-- EMPTY -->

        <div
            class="text-center py-5">

            <i
                class="bi bi-receipt empty-icon">
            </i>

            <h3 class="mt-4">

                No Orders Yet

            </h3>

            <p class="text-secondary">

                You haven't placed any orders yet.

            </p>

            <a
                href="menu.php"
                class="btn btn-warning">

                <i class="bi bi-shop"></i>

                Browse Menu

            </a>

        </div>


    <?php endif; ?>


</div>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>