
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
// GET USER ID
// ========================================

$user_id = isset($_GET["id"])
    ? (int) $_GET["id"]
    : 0;


if ($user_id <= 0) {

    header("Location: users.php");
    exit;
}


// ========================================
// GET USER DETAILS
// ========================================

$stmt = $conn->prepare(
    "SELECT
        id,
        name,
        email,
        role,
        created_at
     FROM users
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();


// ========================================
// USER NOT FOUND
// ========================================

if ($result->num_rows === 0) {

    $stmt->close();

    header("Location: users.php");
    exit;
}


$user = $result->fetch_assoc();

$stmt->close();


// ========================================
// USER DATA
// ========================================

$name = $user["name"];
$email = $user["email"];
$role = $user["role"];
$created_at = $user["created_at"];


// ========================================
// AVATAR LETTER
// ========================================

$avatar = strtoupper(
    substr($name, 0, 1)
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        View User | BiteCraft Admin
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

            background-color: #f5f6f8;

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

            padding: 25px 15px;

        }


        .sidebar-brand {

            color: white;

            text-decoration: none;

            font-size: 24px;

            font-weight: bold;

            display: block;

            padding: 10px 15px;

            margin-bottom: 25px;

        }


        .sidebar-brand:hover {

            color: white;

        }


        .sidebar-link {

            display: flex;

            align-items: center;

            gap: 12px;

            color: #adb5bd;

            text-decoration: none;

            padding: 12px 15px;

            border-radius: 10px;

            margin-bottom: 5px;

            transition: 0.2s;

        }


        .sidebar-link:hover {

            background-color: #343a40;

            color: white;

        }


        .sidebar-link.active {

            background-color: #ffc107;

            color: #212529;

            font-weight: 600;

        }


        .sidebar-link i {

            width: 20px;

        }


        /* ========================================
           MAIN CONTENT
        ======================================== */

        .main-content {

            margin-left: 250px;

            padding: 30px;

            min-height: 100vh;

        }


        /* ========================================
           TOPBAR
        ======================================== */

        .topbar {

            background-color: white;

            border-radius: 15px;

            padding: 18px 25px;

            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.05);

            margin-bottom: 25px;

        }


        /* ========================================
           USER CARD
        ======================================== */

        .user-card {

            background-color: white;

            border: none;

            border-radius: 15px;

            box-shadow:
                0 4px 15px
                rgba(0, 0, 0, 0.05);

            overflow: hidden;

        }


        .user-header {

            background-color: #212529;

            padding: 40px;

            color: white;

        }


        .large-avatar {

            width: 90px;

            height: 90px;

            border-radius: 50%;

            background-color: #ffc107;

            color: #212529;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 36px;

            font-weight: bold;

            margin-bottom: 20px;

        }


        .user-info {

            padding: 30px;

        }


        .info-box {

            background-color: #f8f9fa;

            border-radius: 12px;

            padding: 20px;

            height: 100%;

        }


        .info-label {

            color: #6c757d;

            font-size: 14px;

            margin-bottom: 6px;

        }


        .info-value {

            font-size: 17px;

            font-weight: 600;

            word-break: break-word;

        }


        .action-buttons {

            padding: 0 30px 30px 30px;

        }


        /* ========================================
           MOBILE
        ======================================== */

        @media (max-width: 767px) {

            .sidebar {

                position: relative;

                width: 100%;

                min-height: auto;

                padding: 15px;

            }


            .sidebar-brand {

                margin-bottom: 15px;

            }


            .sidebar-link {

                display: inline-flex;

                margin-right: 5px;

            }


            .main-content {

                margin-left: 0;

                padding: 20px;

            }


            .user-header {

                padding: 30px 20px;

            }


            .user-info {

                padding: 20px;

            }


            .action-buttons {

                padding: 0 20px 20px 20px;

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
        class="sidebar-brand">

        <i class="bi bi-egg-fried text-warning"></i>

        Bite<span class="text-warning">Craft</span>

        <small
            class="d-block text-secondary fs-6 mt-1">

            Admin Panel

        </small>

    </a>


    <!-- DASHBOARD -->

    <a
        href="index.php"
        class="sidebar-link">

        <i class="bi bi-speedometer2"></i>

        Dashboard

    </a>


    <!-- MENU -->

    <a
        href="menu.php"
        class="sidebar-link">

        <i class="bi bi-grid"></i>

        Menu Items

    </a>


    <!-- ADD MENU -->

    <a
        href="add-menu.php"
        class="sidebar-link">

        <i class="bi bi-plus-circle"></i>

        Add Menu Item

    </a>


    <!-- ORDERS -->

    <a
        href="orders.php"
        class="sidebar-link">

        <i class="bi bi-receipt"></i>

        Orders

    </a>


    <!-- RESERVATIONS -->

    <a
        href="reservations.php"
        class="sidebar-link">

        <i class="bi bi-calendar-check"></i>

        Reservations

    </a>


    <!-- USERS -->

    <a
        href="users.php"
        class="sidebar-link active">

        <i class="bi bi-people"></i>

        Users

    </a>


    <hr class="border-secondary my-4">


    <!-- WEBSITE -->

    <a
        href="../index.php"
        class="sidebar-link">

        <i class="bi bi-house"></i>

        View Website

    </a>


    <!-- LOGOUT -->

    <a
        href="logout.php"
        class="sidebar-link text-danger">

        <i class="bi bi-box-arrow-right"></i>

        Logout

    </a>


</aside>



<!-- ========================================
     MAIN CONTENT
======================================== -->

<main class="main-content">


    <!-- TOP BAR -->

    <div
        class="topbar d-flex justify-content-between align-items-center flex-wrap gap-3">


        <div>

            <h4 class="fw-bold mb-1">

                User Details

            </h4>

            <small class="text-secondary">

                View registered user information

            </small>

        </div>


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



    <!-- ========================================
         PAGE HEADER
    ======================================== -->

    <div
        class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">


        <div>

            <h2 class="fw-bold mb-1">

                User Profile

            </h2>

            <p class="text-secondary mb-0">

                Detailed information about this user.

            </p>

        </div>


        <a
            href="users.php"
            class="btn btn-outline-dark">

            <i class="bi bi-arrow-left"></i>

            Back to Users

        </a>


    </div>



    <!-- ========================================
         USER CARD
    ======================================== -->

    <div class="user-card">


        <!-- USER HEADER -->

        <div class="user-header">


            <div class="large-avatar">

                <?php

                echo htmlspecialchars($avatar);

                ?>

            </div>


            <h3 class="fw-bold mb-2">

                <?php

                echo htmlspecialchars($name);

                ?>

            </h3>


            <p class="mb-3 text-light">

                <i class="bi bi-envelope me-2"></i>

                <?php

                echo htmlspecialchars($email);

                ?>

            </p>


            <?php if ($role === "admin"): ?>

                <span class="badge bg-warning text-dark">

                    <i class="bi bi-shield-check"></i>

                    Administrator

                </span>

            <?php else: ?>

                <span class="badge bg-success">

                    <i class="bi bi-person-check"></i>

                    Customer

                </span>

            <?php endif; ?>


        </div>



        <!-- USER INFORMATION -->

        <div class="user-info">


            <h5 class="fw-bold mb-4">

                <i class="bi bi-info-circle text-warning"></i>

                Account Information

            </h5>


            <div class="row g-4">


                <!-- USER ID -->

                <div class="col-md-6">

                    <div class="info-box">

                        <div class="info-label">

                            User ID

                        </div>

                        <div class="info-value">

                            #<?php

                            echo (int) $user["id"];

                            ?>

                        </div>

                    </div>

                </div>


                <!-- NAME -->

                <div class="col-md-6">

                    <div class="info-box">

                        <div class="info-label">

                            Full Name

                        </div>

                        <div class="info-value">

                            <?php

                            echo htmlspecialchars($name);

                            ?>

                        </div>

                    </div>

                </div>


                <!-- EMAIL -->

                <div class="col-md-6">

                    <div class="info-box">

                        <div class="info-label">

                            Email Address

                        </div>

                        <div class="info-value">

                            <?php

                            echo htmlspecialchars($email);

                            ?>

                        </div>

                    </div>

                </div>


                <!-- ROLE -->

                <div class="col-md-6">

                    <div class="info-box">

                        <div class="info-label">

                            Account Role

                        </div>

                        <div class="info-value">


                            <?php if ($role === "admin"): ?>

                                <span class="badge bg-dark">

                                    <i class="bi bi-shield-check"></i>

                                    Admin

                                </span>

                            <?php else: ?>

                                <span class="badge bg-success">

                                    <i class="bi bi-person"></i>

                                    Customer

                                </span>

                            <?php endif; ?>


                        </div>

                    </div>

                </div>


                <!-- REGISTERED DATE -->

                <div class="col-md-6">

                    <div class="info-box">

                        <div class="info-label">

                            Registered Date

                        </div>

                        <div class="info-value">

                            <i class="bi bi-calendar3 text-warning me-1"></i>

                            <?php

                            echo date(
                                "d M Y",
                                strtotime($created_at)
                            );

                            ?>

                        </div>

                    </div>

                </div>


                <!-- REGISTERED TIME -->

                <div class="col-md-6">

                    <div class="info-box">

                        <div class="info-label">

                            Registered Time

                        </div>

                        <div class="info-value">

                            <i class="bi bi-clock text-warning me-1"></i>

                            <?php

                            echo date(
                                "h:i A",
                                strtotime($created_at)
                            );

                            ?>

                        </div>

                    </div>

                </div>


            </div>


        </div>



        <!-- ACTION BUTTONS -->

        <div class="action-buttons">


            <a
                href="users.php"
                class="btn btn-dark">

                <i class="bi bi-people me-1"></i>

                Back to Users

            </a>


        </div>


    </div>


</main>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>

