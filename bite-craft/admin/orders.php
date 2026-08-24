<?php

session_start();

require_once "../config/database.php";


// ========================================
// CHECK ADMIN LOGIN
// ========================================

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit;
}


// ========================================
// CHECK ADMIN ROLE
// ========================================

if ($_SESSION["user_role"] !== "admin") {

    header("Location: ../index.php");
    exit;
}


// ========================================
// GET ALL ORDERS
// ========================================

$sql = "
    SELECT *
    FROM orders
    ORDER BY id DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Orders | BiteCraft</title>


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

        .main-content {

            margin-left: 250px;

            min-height: 100vh;
        }

        .topbar {

            background-color: white;

            padding: 15px 30px;

            border-bottom: 1px solid #dee2e6;
        }

        .content-card {

            background-color: white;

            border-radius: 15px;

            border: 0;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.05);
        }

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


    <a
        href="index.php"
        class="sidebar-logo">

        <i class="bi bi-egg-fried"></i>

        Bite<span>Craft</span>

    </a>


    <!-- Dashboard -->

    <a
        href="index.php"
        class="sidebar-link">

        <i class="bi bi-speedometer2"></i>

        <span>Dashboard</span>

    </a>


    <!-- Menu -->

    <a
        href="menu.php"
        class="sidebar-link">

        <i class="bi bi-egg-fried"></i>

        <span>Menu Items</span>

    </a>


    <!-- Add Menu -->

    <a
        href="add-menu.php"
        class="sidebar-link">

        <i class="bi bi-plus-circle"></i>

        <span>Add Menu Item</span>

    </a>


    <!-- Orders -->

    <a
        href="orders.php"
        class="sidebar-link active">

        <i class="bi bi-receipt"></i>

        <span>Orders</span>

    </a>


    <!-- Reservations -->

    <a
        href="reservations.php"
        class="sidebar-link">

        <i class="bi bi-calendar-check"></i>

        <span>Reservations</span>

    </a>


    <!-- Users -->

    <a
        href="users.php"
        class="sidebar-link">

        <i class="bi bi-people"></i>

        <span>Users</span>

    </a>


    <hr class="border-secondary mx-3">


    <!-- Website -->

    <a
        href="../index.php"
        class="sidebar-link">

        <i class="bi bi-globe"></i>

        <span>View Website</span>

    </a>


    <!-- Logout -->

    <a
        href="logout.php"
        class="sidebar-link">

        <i class="bi bi-box-arrow-right"></i>

        <span>Logout</span>

    </a>


</aside>



<!-- ========================================
     MAIN
======================================== -->

<main class="main-content">


    <!-- TOPBAR -->

    <div
        class="topbar d-flex justify-content-between align-items-center">

        <h5 class="mb-0 fw-bold">

            Orders

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



    <!-- CONTENT -->

    <div class="container-fluid p-4">


        <div
            class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold">

                    Customer Orders

                </h2>

                <p class="text-secondary">

                    View and manage customer orders.

                </p>

            </div>


            <a
                href="index.php"
                class="btn btn-outline-dark">

                <i class="bi bi-arrow-left"></i>

                Dashboard

            </a>

        </div>



        <!-- ORDERS TABLE -->

        <div class="content-card p-4">


            <div class="table-responsive">


                <table
                    class="table table-hover align-middle">


                    <thead>

                        <tr>

                            <th>Order ID</th>

                            <th>Customer</th>

                            <th>Phone</th>

                            <th>Total</th>

                            <th>Status</th>

                            <th>Date</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php if (
                        $result &&
                        $result->num_rows > 0
                    ): ?>


                        <?php while (
                            $order =
                            $result->fetch_assoc()
                        ): ?>


                            <tr>


                                <!-- ID -->

                                <td>

                                    <strong>

                                        #<?php

                                        echo $order["id"];

                                        ?>

                                    </strong>

                                </td>


                                <!-- CUSTOMER -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $order["customer_name"]
                                    );

                                    ?>

                                </td>


                                <!-- PHONE -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $order["phone"]
                                    );

                                    ?>

                                </td>


                                <!-- TOTAL -->

                                <td>

                                    <strong>

                                        Rs.

                                        <?php

                                        echo number_format(
                                            $order["total_amount"],
                                            2
                                        );

                                        ?>

                                    </strong>

                                </td>


                                <!-- STATUS -->

                                <td>


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


                                </td>


                                <!-- DATE -->

                                <td>

                                    <?php

                                    echo date(
                                        "d M Y",
                                        strtotime(
                                            $order[
                                                "created_at"
                                            ]
                                        )
                                    );

                                    ?>

                                </td>


                                <!-- VIEW -->

                                <td>

                                    <a
                                        href="view-order.php?id=<?php echo $order["id"]; ?>"
                                        class="btn btn-sm btn-primary">

                                        <i
                                            class="bi bi-eye">
                                        </i>

                                        View

                                    </a>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5">

                                <i
                                    class="bi bi-receipt"
                                    style="font-size: 50px;">
                                </i>


                                <h5 class="mt-3">

                                    No Orders Found

                                </h5>


                                <p class="text-secondary">

                                    Customer orders will appear here.

                                </p>

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>

                </table>


            </div>


        </div>


    </div>


</main>


</body>

</html>