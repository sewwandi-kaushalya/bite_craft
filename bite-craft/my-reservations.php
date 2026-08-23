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
// GET LOGGED-IN USER ID
// ========================================

$user_id = (int) $_SESSION["user_id"];


// ========================================
// GET USER RESERVATIONS
// ========================================

$stmt = $conn->prepare(
    "SELECT
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
     WHERE user_id = ?
     ORDER BY reservation_date DESC,
              reservation_time DESC,
              id DESC"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$reservations = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        My Reservations | BiteCraft
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


        .reservation-card {

            background-color: white;

            border: none;

            border-radius: 15px;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.06);

            transition: 0.3s;

        }


        .reservation-card:hover {

            transform: translateY(-3px);

            box-shadow:
                0 10px 25px
                rgba(0, 0, 0, 0.10);

        }


        .reservation-id {

            font-size: 20px;

            font-weight: bold;

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


        .detail-item {

            margin-bottom: 15px;

        }


        .detail-item:last-child {

            margin-bottom: 0;

        }


        .detail-label {

            color: #6c757d;

            font-size: 13px;

        }


        .empty-icon {

            font-size: 80px;

            color: #adb5bd;

        }


        .status-badge {

            padding: 8px 12px;

            font-size: 13px;

        }


        .date-box {

            background-color: #f8f9fa;

            border-radius: 10px;

            padding: 12px;

        }


        .price-text {

            color: #dc9f00;

            font-weight: bold;

        }

    </style>

</head>


<body>


<!-- ========================================
     NAVBAR
======================================== -->

<nav
    class="navbar navbar-dark bg-dark">

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


        <!-- NAVIGATION -->

        <div class="d-flex gap-2">


            <a
                href="index.php"
                class="btn btn-outline-light btn-sm">

                <i class="bi bi-house"></i>

                Home

            </a>


            <a
                href="menu.php"
                class="btn btn-warning btn-sm">

                <i class="bi bi-shop"></i>

                Menu

            </a>


        </div>

    </div>

</nav>



<!-- ========================================
     CONTENT
======================================== -->

<div class="container py-5">


    <!-- PAGE HEADER -->

    <div
        class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">


        <div>

            <h2 class="fw-bold mb-1">

                <i
                    class="bi bi-calendar-check text-warning">
                </i>

                My Reservations

            </h2>

            <p class="text-secondary mb-0">

                View your table reservations and their status.

            </p>

        </div>


        <a
            href="reservation.php"
            class="btn btn-warning">

            <i
                class="bi bi-plus-circle me-1">
            </i>

            New Reservation

        </a>


    </div>



    <!-- ========================================
         RESERVATIONS
    ======================================== -->

    <?php if (
        $reservations &&
        $reservations->num_rows > 0
    ): ?>


        <div class="row g-4">


            <?php while (
                $reservation =
                $reservations->fetch_assoc()
            ): ?>


                <?php

                // ====================================
                // STATUS BADGE
                // ====================================

                $status =
                    $reservation["status"];

                $badge =
                    "secondary";

                switch ($status) {

                    case "pending":

                        $badge =
                            "warning";

                        break;


                    case "confirmed":

                        $badge =
                            "success";

                        break;


                    case "completed":

                        $badge =
                            "dark";

                        break;


                    case "cancelled":

                        $badge =
                            "danger";

                        break;

                }

                ?>


                <!-- ====================================
                     RESERVATION CARD
                ===================================== -->

                <div class="col-md-6 col-lg-4">


                    <div
                        class="card reservation-card h-100">


                        <div
                            class="card-body p-4">


                            <!-- HEADER -->

                            <div
                                class="d-flex justify-content-between align-items-center mb-3">


                                <div
                                    class="d-flex align-items-center gap-3">


                                    <div
                                        class="reservation-icon">

                                        <i
                                            class="bi bi-calendar-heart">
                                        </i>

                                    </div>


                                    <div>

                                        <small
                                            class="text-secondary">

                                            Reservation ID

                                        </small>

                                        <div
                                            class="reservation-id">

                                            #<?php

                                            echo (int)
                                                $reservation["id"];

                                            ?>

                                        </div>

                                    </div>

                                </div>


                                <!-- STATUS -->

                                <span
                                    class="badge bg-<?php echo $badge; ?> status-badge">

                                    <?php

                                    echo ucfirst(
                                        $status
                                    );

                                    ?>

                                </span>


                            </div>


                            <hr>



                            <!-- ====================================
                                 DATE & TIME
                            ===================================== -->

                            <div
                                class="date-box mb-3">


                                <div
                                    class="row">


                                    <div
                                        class="col-6">

                                        <div
                                            class="detail-label">

                                            <i
                                                class="bi bi-calendar3">
                                            </i>

                                            Date

                                        </div>


                                        <div
                                            class="fw-semibold">

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


                                    <div
                                        class="col-6">

                                        <div
                                            class="detail-label">

                                            <i
                                                class="bi bi-clock">
                                            </i>

                                            Time

                                        </div>


                                        <div
                                            class="fw-semibold">

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


                                </div>


                            </div>



                            <!-- ====================================
                                 GUESTS
                            ===================================== -->

                            <div
                                class="detail-item">


                                <div
                                    class="detail-label">

                                    <i
                                        class="bi bi-people">
                                    </i>

                                    Guests

                                </div>


                                <div
                                    class="fw-semibold">

                                    <?php

                                    echo (int)
                                        $reservation[
                                            "guests"
                                        ];

                                    ?>

                                    <?php

                                    echo (
                                        (int)
                                        $reservation[
                                            "guests"
                                        ] === 1
                                    )
                                        ? " Guest"
                                        : " Guests";

                                    ?>

                                </div>


                            </div>



                            <!-- ====================================
                                 CUSTOMER NAME
                            ===================================== -->

                            <div
                                class="detail-item">


                                <div
                                    class="detail-label">

                                    <i
                                        class="bi bi-person">
                                    </i>

                                    Customer

                                </div>


                                <div
                                    class="fw-semibold">

                                    <?php

                                    echo htmlspecialchars(
                                        $reservation[
                                            "name"
                                        ]
                                    );

                                    ?>

                                </div>


                            </div>



                            <!-- ====================================
                                 PHONE
                            ===================================== -->

                            <div
                                class="detail-item">


                                <div
                                    class="detail-label">

                                    <i
                                        class="bi bi-telephone">
                                    </i>

                                    Phone

                                </div>


                                <div
                                    class="fw-semibold">

                                    <?php

                                    echo htmlspecialchars(
                                        $reservation[
                                            "phone"
                                        ]
                                    );

                                    ?>

                                </div>


                            </div>



                            <!-- ====================================
                                 VIEW BUTTON
                            ===================================== -->

                            <a
                                href="view-reservation.php?id=<?php echo (int) $reservation["id"]; ?>"
                                class="btn btn-outline-dark w-100 mt-2">


                                <i
                                    class="bi bi-eye me-1">
                                </i>

                                View Reservation


                            </a>


                        </div>


                    </div>


                </div>


            <?php endwhile; ?>


        </div>


    <?php else: ?>


        <!-- ========================================
             EMPTY STATE
        ======================================== -->

        <div
            class="text-center py-5">


            <i
                class="bi bi-calendar-x empty-icon">
            </i>


            <h3
                class="fw-bold mt-4">

                No Reservations Yet

            </h3>


            <p
                class="text-secondary">

                You haven't made any table reservations yet.

            </p>


            <a
                href="reservation.php"
                class="btn btn-warning">

                <i
                    class="bi bi-calendar-plus me-1">
                </i>

                Reserve a Table

            </a>


        </div>


    <?php endif; ?>


</div>



<!-- ========================================
     FOOTER
======================================== -->

<footer
    class="bg-dark text-white py-4 mt-5">

    <div class="container">


        <div
            class="text-center">


            <div>

                <i
                    class="bi bi-egg-fried text-warning">
                </i>

                <strong>

                    BiteCraft

                </strong>

            </div>


            <div
                class="text-secondary mt-2">

                © 2026 BiteCraft Restaurant.
                All Rights Reserved.

            </div>


        </div>

    </div>

</footer>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>