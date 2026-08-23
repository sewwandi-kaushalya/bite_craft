
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

if (
    !isset($_SESSION["user_role"]) ||
    $_SESSION["user_role"] !== "admin"
) {

    header("Location: ../index.php");
    exit;
}


// ========================================
// GET ALL RESERVATIONS
// ========================================

$sql = "
    SELECT
        id,
        name,
        email,
        phone,
        guests,
        reservation_date,
        reservation_time,
        special_request,
        status,
        created_at
    FROM reservations
    ORDER BY reservation_date DESC,
             reservation_time DESC,
             id DESC
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

    <title>
        Reservations | BiteCraft
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


        /* ========================================
           SIDEBAR
        ======================================== */

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


        /* ========================================
           MAIN CONTENT
        ======================================== */

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


        .table th {

            white-space: nowrap;

        }


        .table td {

            vertical-align: middle;

        }


        /* ========================================
           MOBILE
        ======================================== */

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
        class="sidebar-link">

        <i class="bi bi-receipt"></i>

        <span>Orders</span>

    </a>


    <!-- RESERVATIONS -->

    <a
        href="reservations.php"
        class="sidebar-link active">

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

            Reservations

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


        <!-- HEADER -->

        <div
            class="d-flex justify-content-between align-items-center mb-4">


            <div>

                <h2 class="fw-bold">

                    Table Reservations

                </h2>

                <p class="text-secondary mb-0">

                    View and manage customer table reservations.

                </p>

            </div>


            <a
                href="index.php"
                class="btn btn-outline-dark">

                <i class="bi bi-arrow-left"></i>

                Dashboard

            </a>


        </div>



        <!-- ====================================
             RESERVATIONS TABLE
        ===================================== -->

        <div class="content-card p-4">


            <div class="table-responsive">


                <table
                    class="table table-hover align-middle">


                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Time
                            </th>

                            <th>
                                Guests
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php if (
                        $result &&
                        $result->num_rows > 0
                    ): ?>


                        <?php while (
                            $reservation =
                            $result->fetch_assoc()
                        ): ?>


                            <tr>


                                <!-- ID -->

                                <td>

                                    <strong>

                                        #<?php

                                        echo (int)
                                            $reservation["id"];

                                        ?>

                                    </strong>

                                </td>



                                <!-- CUSTOMER -->

                                <td>

                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $reservation["name"]
                                        );

                                        ?>

                                    </strong>


                                    <br>


                                    <small
                                        class="text-secondary">

                                        <?php

                                        echo htmlspecialchars(
                                            $reservation["email"]
                                        );

                                        ?>

                                    </small>

                                </td>



                                <!-- PHONE -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $reservation["phone"]
                                    );

                                    ?>

                                </td>



                                <!-- DATE -->

                                <td>

                                    <i
                                        class="bi bi-calendar3 text-warning">
                                    </i>

                                    <?php

                                    echo date(
                                        "d M Y",
                                        strtotime(
                                            $reservation[
                                                "reservation_date"
                                            ]
                                        )
                                    );

                                    ?>

                                </td>



                                <!-- TIME -->

                                <td>

                                    <i
                                        class="bi bi-clock text-warning">
                                    </i>

                                    <?php

                                    echo date(
                                        "h:i A",
                                        strtotime(
                                            $reservation[
                                                "reservation_time"
                                            ]
                                        )
                                    );

                                    ?>

                                </td>



                                <!-- GUESTS -->

                                <td>

                                    <span
                                        class="badge bg-light text-dark border">

                                        <i
                                            class="bi bi-people">
                                        </i>

                                        <?php

                                        echo (int)
                                            $reservation[
                                                "guests"
                                            ];

                                        ?>

                                    </span>

                                </td>



                                <!-- STATUS -->

                                <td>


                                    <?php

                                    $status =
                                        $reservation[
                                            "status"
                                        ];

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



                                <!-- ACTION -->

                                <td>

                                    <a
                                        href="view-reservation.php?id=<?php echo (int) $reservation["id"]; ?>"
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


                        <!-- NO RESERVATIONS -->

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5">


                                <i
                                    class="bi bi-calendar-x"
                                    style="font-size: 55px;">
                                </i>


                                <h5 class="mt-3">

                                    No Reservations Found

                                </h5>


                                <p
                                    class="text-secondary mb-0">

                                    Customer reservations
                                    will appear here.

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

