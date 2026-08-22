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


// Check role

if ($_SESSION["user_role"] !== "admin") {

    header("Location: ../index.php");

    exit;
}


// ========================================
// GET ADMIN NAME
// ========================================

$admin_name = $_SESSION["user_name"];


// ========================================
// GET TOTAL USERS
// ========================================

$total_users = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM users"
);

if ($result) {

    $row = $result->fetch_assoc();

    $total_users = $row["total"];
}


// ========================================
// GET TOTAL MENU ITEMS
// ========================================

$total_menu = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM menu_items"
);

if ($result) {

    $row = $result->fetch_assoc();

    $total_menu = $row["total"];
}


// ========================================
// GET TOTAL CATEGORIES
// ========================================

$total_categories = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM categories"
);

if ($result) {

    $row = $result->fetch_assoc();

    $total_categories = $row["total"];
}


// ========================================
// GET TOTAL RESERVATIONS
// ========================================

$total_reservations = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM reservations"
);

if ($result) {

    $row = $result->fetch_assoc();

    $total_reservations = $row["total"];
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | BiteCraft</title>


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


        /* Sidebar */

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


        /* Main */

        .main-content {

            margin-left: 250px;

            min-height: 100vh;

        }


        /* Topbar */

        .topbar {

            background-color: white;

            padding: 15px 30px;

            border-bottom: 1px solid #dee2e6;

        }


        /* Stat Cards */

        .stat-card {

            background-color: white;

            border-radius: 15px;

            padding: 25px;

            border: 0;

            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);

            transition: 0.3s;

        }


        .stat-card:hover {

            transform: translateY(-5px);

        }


        .stat-icon {

            width: 55px;

            height: 55px;

            border-radius: 12px;

            display: flex;

            justify-content: center;

            align-items: center;

            font-size: 25px;

            background-color: #fff3cd;

            color: #ffc107;

        }


        /* Quick Actions */

        .action-card {

            background-color: white;

            border-radius: 15px;

            padding: 25px;

            text-decoration: none;

            color: #212529;

            display: block;

            border: 1px solid #eee;

            transition: 0.3s;

        }


        .action-card:hover {

            color: #212529;

            transform: translateY(-5px);

            box-shadow: 0 10px 25px rgba(0,0,0,0.08);

        }


        .action-icon {

            font-size: 35px;

            color: #ffc107;

        }


        /* Mobile */

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


    <!-- Logo -->

    <a
        href="index.php"
        class="sidebar-logo">

        <i class="bi bi-egg-fried"></i>

        Bite<span>Craft</span>

    </a>



    <!-- Dashboard -->

    <a
        href="index.php"
        class="sidebar-link active">

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



    <!-- Reservations -->

    <a
        href="#"
        class="sidebar-link">

        <i class="bi bi-calendar-check"></i>

        <span>Reservations</span>

    </a>



    <!-- Users -->

    <a
        href="#"
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
     MAIN CONTENT
======================================== -->


<main class="main-content">


    <!-- TOPBAR -->

    <div class="topbar d-flex justify-content-between align-items-center">


        <div>

            <h5 class="mb-0 fw-bold">

                Dashboard

            </h5>

        </div>



        <div>

            <i class="bi bi-person-circle me-2"></i>

            <strong>

                <?php echo htmlspecialchars($admin_name); ?>

            </strong>

        </div>


    </div>



    <!-- PAGE CONTENT -->

    <div class="container-fluid p-4">


        <!-- Welcome -->

        <div class="mb-4">

            <h2 class="fw-bold">

                Welcome back,
                <?php echo htmlspecialchars($admin_name); ?>! 👋

            </h2>

            <p class="text-secondary">

                Here's what's happening with BiteCraft today.

            </p>

        </div>



        <!-- ====================================
             STAT CARDS
        ==================================== -->


        <div class="row g-4 mb-5">


            <!-- USERS -->

            <div class="col-sm-6 col-xl-3">


                <div class="stat-card">


                    <div class="d-flex justify-content-between">


                        <div>

                            <p class="text-secondary mb-1">

                                Total Users

                            </p>


                            <h2 class="fw-bold mb-0">

                                <?php echo $total_users; ?>

                            </h2>

                        </div>


                        <div class="stat-icon">

                            <i class="bi bi-people"></i>

                        </div>


                    </div>


                </div>


            </div>



            <!-- MENU -->

            <div class="col-sm-6 col-xl-3">


                <div class="stat-card">


                    <div class="d-flex justify-content-between">


                        <div>

                            <p class="text-secondary mb-1">

                                Menu Items

                            </p>


                            <h2 class="fw-bold mb-0">

                                <?php echo $total_menu; ?>

                            </h2>

                        </div>


                        <div class="stat-icon">

                            <i class="bi bi-egg-fried"></i>

                        </div>


                    </div>


                </div>


            </div>



            <!-- CATEGORIES -->

            <div class="col-sm-6 col-xl-3">


                <div class="stat-card">


                    <div class="d-flex justify-content-between">


                        <div>

                            <p class="text-secondary mb-1">

                                Categories

                            </p>


                            <h2 class="fw-bold mb-0">

                                <?php echo $total_categories; ?>

                            </h2>

                        </div>


                        <div class="stat-icon">

                            <i class="bi bi-grid"></i>

                        </div>


                    </div>


                </div>


            </div>



            <!-- RESERVATIONS -->

            <div class="col-sm-6 col-xl-3">


                <div class="stat-card">


                    <div class="d-flex justify-content-between">


                        <div>

                            <p class="text-secondary mb-1">

                                Reservations

                            </p>


                            <h2 class="fw-bold mb-0">

                                <?php echo $total_reservations; ?>

                            </h2>

                        </div>


                        <div class="stat-icon">

                            <i class="bi bi-calendar-check"></i>

                        </div>


                    </div>


                </div>


            </div>


        </div>



        <!-- ====================================
             QUICK ACTIONS
        ==================================== -->


        <h4 class="fw-bold mb-3">

            Quick Actions

        </h4>


        <div class="row g-4">


            <!-- Add Menu -->

            <div class="col-md-4">


                <a
                    href="add-menu.php"
                    class="action-card">


                    <i
                        class="bi bi-plus-circle action-icon">
                    </i>


                    <h5 class="fw-bold mt-3">

                        Add Menu Item

                    </h5>


                    <p class="text-secondary mb-0">

                        Add a new food item to your restaurant menu.

                    </p>


                </a>


            </div>



            <!-- Manage Menu -->

            <div class="col-md-4">


                <a
                    href="menu.php"
                    class="action-card">


                    <i
                        class="bi bi-list-ul action-icon">
                    </i>


                    <h5 class="fw-bold mt-3">

                        Manage Menu

                    </h5>


                    <p class="text-secondary mb-0">

                        View, edit and delete menu items.

                    </p>


                </a>


            </div>



            <!-- Website -->

            <div class="col-md-4">


                <a
                    href="../index.php"
                    class="action-card">


                    <i
                        class="bi bi-globe2 action-icon">
                    </i>


                    <h5 class="fw-bold mt-3">

                        View Website

                    </h5>


                    <p class="text-secondary mb-0">

                        Open the customer-facing restaurant website.

                    </p>


                </a>


            </div>


        </div>


    </div>


</main>


</body>

</html>