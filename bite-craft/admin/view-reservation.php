
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
// GET RESERVATION ID
// ========================================

$reservation_id = isset($_GET["id"])
    ? (int) $_GET["id"]
    : 0;

if ($reservation_id <= 0) {

    header("Location: reservations.php");
    exit;
}

// ========================================
// UPDATE RESERVATION STATUS
// ========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $status = $_POST["status"] ?? "";

    $allowed_statuses = [
        "pending",
        "confirmed",
        "cancelled"
    ];

    if (in_array($status, $allowed_statuses, true)) {

        $update = $conn->prepare(
            "UPDATE reservations
             SET status = ?
             WHERE id = ?"
        );

        $update->bind_param(
            "si",
            $status,
            $reservation_id
        );

        $update->execute();

        $update->close();

        header(
            "Location: view-reservation.php?id="
            . $reservation_id
        );

        exit;
    }
}

// ========================================
// GET RESERVATION
// ========================================

$stmt = $conn->prepare(
    "SELECT id,
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
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param(
    "i",
    $reservation_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    $stmt->close();

    header("Location: reservations.php");
    exit;
}

$reservation = $result->fetch_assoc();

$stmt->close();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Reservation #<?php echo $reservation_id; ?> | BiteCraft
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

        .detail-card {

            background-color: white;

            border-radius: 15px;

            border: 0;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.05);
        }

        .info-label {

            color: #6c757d;

            font-size: 14px;

            margin-bottom: 4px;
        }

        .info-value {

            font-weight: 600;

            font-size: 16px;
        }

        .reservation-icon {

            width: 55px;

            height: 55px;

            border-radius: 50%;

            background-color: #fff3cd;

            color: #ffc107;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 25px;
        }

        .status-box {

            background-color: #f8f9fa;

            border-radius: 12px;

            padding: 20px;
        }

        .request-box {

            background-color: #f8f9fa;

            border-left: 4px solid #ffc107;

            padding: 15px;

            border-radius: 8px;
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
        class="sidebar-link">

        <i class="bi bi-receipt"></i>

        <span>Orders</span>

    </a>


    <!-- Reservations -->

    <a
        href="reservations.php"
        class="sidebar-link active">

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

    <div
        class="topbar d-flex justify-content-between align-items-center">

        <h5 class="mb-0 fw-bold">

            Reservation #<?php echo $reservation_id; ?>

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

                    Reservation Details

                </h2>

                <p class="text-secondary mb-0">

                    View and manage customer reservation.

                </p>

            </div>


            <a
                href="reservations.php"
                class="btn btn-outline-dark">

                <i class="bi bi-arrow-left"></i>

                Back to Reservations

            </a>

        </div>



        <div class="row g-4">


            <!-- ====================================
                 CUSTOMER DETAILS
            ===================================== -->

            <div class="col-lg-7">


                <div
                    class="detail-card p-4">


                    <!-- TITLE -->

                    <div
                        class="d-flex align-items-center gap-3 mb-4">

                        <div class="reservation-icon">

                            <i class="bi bi-person"></i>

                        </div>


                        <div>

                            <h4 class="fw-bold mb-1">

                                Customer Details

                            </h4>

                            <p
                                class="text-secondary mb-0">

                                Reservation information

                            </p>

                        </div>

                    </div>



                    <div class="row g-4">


                        <!-- NAME -->

                        <div class="col-md-6">

                            <div class="info-label">

                                Customer Name

                            </div>

                            <div class="info-value">

                                <?php

                                echo htmlspecialchars(
                                    $reservation["name"]
                                );

                                ?>

                            </div>

                        </div>


                        <!-- EMAIL -->

                        <div class="col-md-6">

                            <div class="info-label">

                                Email

                            </div>

                            <div class="info-value">

                                <?php

                                echo htmlspecialchars(
                                    $reservation["email"]
                                );

                                ?>

                            </div>

                        </div>


                        <!-- PHONE -->

                        <div class="col-md-6">

                            <div class="info-label">

                                Phone

                            </div>

                            <div class="info-value">

                                <?php

                                echo htmlspecialchars(
                                    $reservation["phone"]
                                );

                                ?>

                            </div>

                        </div>


                        <!-- GUESTS -->

                        <div class="col-md-6">

                            <div class="info-label">

                                Number of Guests

                            </div>

                            <div class="info-value">

                                <i
                                    class="bi bi-people text-warning">
                                </i>

                                <?php

                                echo (int)
                                    $reservation["guests"];

                                ?>

                                Guests

                            </div>

                        </div>


                    </div>


                    <hr class="my-4">


                    <!-- RESERVATION INFO -->

                    <h5 class="fw-bold mb-4">

                        <i
                            class="bi bi-calendar-check text-warning">
                        </i>

                        Reservation Information

                    </h5>


                    <div class="row g-4">


                        <!-- DATE -->

                        <div class="col-md-6">

                            <div class="info-label">

                                Reservation Date

                            </div>

                            <div class="info-value">

                                <i
                                    class="bi bi-calendar3 text-warning me-1">
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

                            </div>

                        </div>


                        <!-- TIME -->

                        <div class="col-md-6">

                            <div class="info-label">

                                Reservation Time

                            </div>

                            <div class="info-value">

                                <i
                                    class="bi bi-clock text-warning me-1">
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

                            </div>

                        </div>


                        <!-- CREATED -->

                        <div class="col-md-6">

                            <div class="info-label">

                                Booking Created

                            </div>

                            <div class="info-value">

                                <?php

                                echo date(
                                    "d M Y, h:i A",
                                    strtotime(
                                        $reservation[
                                            "created_at"
                                        ]
                                    )
                                );

                                ?>

                            </div>

                        </div>


                    </div>



                    <!-- SPECIAL REQUEST -->

                    <?php if (
                        !empty(
                            $reservation[
                                "special_request"
                            ]
                        )
                    ): ?>


                        <hr class="my-4">


                        <h5 class="fw-bold mb-3">

                            <i
                                class="bi bi-chat-left-text text-warning">
                            </i>

                            Special Request

                        </h5>


                        <div class="request-box">

                            <?php

                            echo nl2br(
                                htmlspecialchars(
                                    $reservation[
                                        "special_request"
                                    ]
                                )
                            );

                            ?>

                        </div>


                    <?php endif; ?>


                </div>


            </div>



            <!-- ====================================
                 STATUS MANAGEMENT
            ===================================== -->

            <div class="col-lg-5">


                <div
                    class="detail-card p-4">


                    <h4 class="fw-bold mb-4">

                        <i
                            class="bi bi-arrow-repeat text-warning">
                        </i>

                        Reservation Status

                    </h4>



                    <?php

                    $status =
                        $reservation["status"];

                    $badge = "secondary";

                    switch ($status) {

                        case "pending":

                            $badge = "warning";

                            break;

                        case "confirmed":

                            $badge = "success";

                            break;

                        case "cancelled":

                            $badge = "danger";

                            break;

                    }

                    ?>


                    <div
                        class="status-box mb-4">

                        <div
                            class="text-secondary mb-2">

                            Current Status

                        </div>


                        <span
                            class="badge bg-<?php echo $badge; ?> fs-6 px-3 py-2">

                            <?php

                            echo ucfirst(
                                $status
                            );

                            ?>

                        </span>

                    </div>



                    <!-- UPDATE STATUS -->

                    <form method="POST">


                        <label
                            class="form-label fw-semibold">

                            Change Status

                        </label>


                        <select
                            name="status"
                            class="form-select mb-3"
                            required>


                            <option
                                value="pending"
                                <?php

                                echo
                                    $status === "pending"
                                    ? "selected"
                                    : "";

                                ?>>

                                Pending

                            </option>


                            <option
                                value="confirmed"
                                <?php

                                echo
                                    $status === "confirmed"
                                    ? "selected"
                                    : "";

                                ?>>

                                Confirmed

                            </option>


                            <option
                                value="cancelled"
                                <?php

                                echo
                                    $status === "cancelled"
                                    ? "selected"
                                    : "";

                                ?>>

                                Cancelled

                            </option>


                        </select>


                        <button
                            type="submit"
                            class="btn btn-warning w-100">

                            <i
                                class="bi bi-check-lg">
                            </i>

                            Update Status

                        </button>


                    </form>


                </div>



                <!-- QUICK INFO -->

                <div
                    class="detail-card p-4 mt-4">


                    <h5 class="fw-bold mb-3">

                        <i
                            class="bi bi-info-circle text-warning">
                        </i>

                        Quick Information

                    </h5>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-secondary">

                            Reservation ID

                        </span>

                        <strong>

                            #<?php echo $reservation_id; ?>

                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-secondary">

                            Guests

                        </span>

                        <strong>

                            <?php

                            echo (int)
                                $reservation["guests"];

                            ?>

                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-secondary">

                            Status

                        </span>

                        <strong>

                            <?php

                            echo ucfirst(
                                $status
                            );

                            ?>

                        </strong>

                    </div>


                </div>


            </div>


        </div>


    </div>


</main>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>

