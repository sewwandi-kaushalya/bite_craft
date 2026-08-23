<?php

session_start();

require_once "config/database.php";

// ========================================
// GET RESERVATION ID
// ========================================

$reservation_id = $_SESSION["last_reservation_id"] ?? 0;

if (!$reservation_id) {

    header("Location: reservation.php");
    exit;
}


// ========================================
// GET RESERVATION DETAILS
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

    header("Location: reservation.php");
    exit;
}

$reservation = $result->fetch_assoc();

$stmt->close();


// ========================================
// REMOVE SESSION ID
// ========================================

unset($_SESSION["last_reservation_id"]);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Reservation Successful | BiteCraft
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


        .success-section {

            min-height: 85vh;

            display: flex;

            align-items: center;

            padding: 60px 0;

        }


        .success-card {

            background-color: white;

            border: none;

            border-radius: 20px;

            box-shadow:
                0 10px 35px
                rgba(0, 0, 0, 0.08);

            overflow: hidden;

        }


        .success-icon {

            width: 90px;

            height: 90px;

            border-radius: 50%;

            background-color: #d1e7dd;

            color: #198754;

            display: flex;

            align-items: center;

            justify-content: center;

            margin: 0 auto;

            font-size: 45px;

        }


        .reservation-number {

            background-color: #fff3cd;

            border-radius: 12px;

            padding: 15px;

        }


        .detail-box {

            background-color: #f8f9fa;

            border-radius: 12px;

            padding: 18px;

            height: 100%;

        }


        .detail-icon {

            color: #ffc107;

            font-size: 22px;

            margin-right: 10px;

        }


        .status-badge {

            font-size: 14px;

            padding: 8px 14px;

        }


        .btn-warning {

            font-weight: 600;

        }

    </style>

</head>


<body>


<!-- ========================================
     NAVBAR
======================================== -->

<nav
    class="navbar navbar-light bg-white shadow-sm">

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
                class="btn btn-outline-dark btn-sm">

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
     SUCCESS SECTION
======================================== -->

<section class="success-section">

    <div class="container">


        <div class="row justify-content-center">

            <div class="col-lg-8">


                <div class="card success-card">


                    <div class="card-body p-4 p-md-5">


                        <!-- SUCCESS ICON -->

                        <div class="success-icon mb-4">

                            <i class="bi bi-check-lg"></i>

                        </div>



                        <!-- TITLE -->

                        <div class="text-center">

                            <h1 class="fw-bold">

                                Reservation Successful!

                            </h1>


                            <p class="text-secondary mt-3">

                                Thank you for choosing
                                BiteCraft.

                                Your table reservation
                                has been received successfully.

                            </p>

                        </div>



                        <!-- RESERVATION NUMBER -->

                        <div
                            class="reservation-number text-center mt-4 mb-4">

                            <small class="text-secondary">

                                Reservation Number

                            </small>

                            <h3 class="fw-bold mb-0">

                                #<?php

                                echo (int)
                                    $reservation["id"];

                                ?>

                            </h3>

                        </div>



                        <!-- STATUS -->

                        <div class="text-center mb-4">

                            <span
                                class="badge bg-warning text-dark status-badge">

                                <i
                                    class="bi bi-clock me-1">
                                </i>

                                Pending Confirmation

                            </span>

                        </div>



                        <!-- DETAILS -->

                        <h4 class="fw-bold mb-3">

                            Reservation Details

                        </h4>



                        <div class="row g-3">


                            <!-- NAME -->

                            <div class="col-md-6">

                                <div class="detail-box">

                                    <small
                                        class="text-secondary">

                                        <i
                                            class="bi bi-person detail-icon">
                                        </i>

                                        Customer Name

                                    </small>

                                    <div
                                        class="fw-semibold mt-2">

                                        <?php

                                        echo htmlspecialchars(
                                            $reservation["name"]
                                        );

                                        ?>

                                    </div>

                                </div>

                            </div>



                            <!-- PHONE -->

                            <div class="col-md-6">

                                <div class="detail-box">

                                    <small
                                        class="text-secondary">

                                        <i
                                            class="bi bi-telephone detail-icon">
                                        </i>

                                        Phone Number

                                    </small>

                                    <div
                                        class="fw-semibold mt-2">

                                        <?php

                                        echo htmlspecialchars(
                                            $reservation["phone"]
                                        );

                                        ?>

                                    </div>

                                </div>

                            </div>



                            <!-- EMAIL -->

                            <div class="col-md-6">

                                <div class="detail-box">

                                    <small
                                        class="text-secondary">

                                        <i
                                            class="bi bi-envelope detail-icon">
                                        </i>

                                        Email

                                    </small>

                                    <div
                                        class="fw-semibold mt-2">

                                        <?php

                                        if (
                                            !empty(
                                                $reservation["email"]
                                            )
                                        ) {

                                            echo htmlspecialchars(
                                                $reservation["email"]
                                            );

                                        } else {

                                            echo "Not provided";

                                        }

                                        ?>

                                    </div>

                                </div>

                            </div>



                            <!-- GUESTS -->

                            <div class="col-md-6">

                                <div class="detail-box">

                                    <small
                                        class="text-secondary">

                                        <i
                                            class="bi bi-people detail-icon">
                                        </i>

                                        Number of Guests

                                    </small>

                                    <div
                                        class="fw-semibold mt-2">

                                        <?php

                                        echo (int)
                                            $reservation["guests"];

                                        ?>

                                        <?php

                                        echo (
                                            (int)
                                            $reservation["guests"] === 1
                                        )
                                            ? " Guest"
                                            : " Guests";

                                        ?>

                                    </div>

                                </div>

                            </div>



                            <!-- DATE -->

                            <div class="col-md-6">

                                <div class="detail-box">

                                    <small
                                        class="text-secondary">

                                        <i
                                            class="bi bi-calendar3 detail-icon">
                                        </i>

                                        Reservation Date

                                    </small>

                                    <div
                                        class="fw-semibold mt-2">

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

                            </div>



                            <!-- TIME -->

                            <div class="col-md-6">

                                <div class="detail-box">

                                    <small
                                        class="text-secondary">

                                        <i
                                            class="bi bi-clock detail-icon">
                                        </i>

                                        Reservation Time

                                    </small>

                                    <div
                                        class="fw-semibold mt-2">

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



                            <!-- SPECIAL REQUEST -->

                            <?php

                            if (
                                !empty(
                                    $reservation[
                                        "special_request"
                                    ]
                                )
                            ):

                            ?>

                                <div class="col-12">

                                    <div class="detail-box">

                                        <small
                                            class="text-secondary">

                                            <i
                                                class="bi bi-chat-left-text detail-icon">
                                            </i>

                                            Special Request

                                        </small>

                                        <div
                                            class="fw-semibold mt-2">

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

                                    </div>

                                </div>

                            <?php endif; ?>


                        </div>



                        <!-- INFORMATION -->

                        <div
                            class="alert alert-light border mt-4">

                            <i
                                class="bi bi-info-circle text-warning me-2">
                            </i>

                            Your reservation is currently

                            <strong>Pending</strong>.

                            Our staff will review your reservation
                            and confirm it shortly.

                        </div>



                        <!-- BUTTONS -->

                        <div
                            class="d-flex justify-content-center gap-3 flex-wrap mt-4">


                            <a
                                href="index.php"
                                class="btn btn-outline-dark px-4">

                                <i class="bi bi-house me-1"></i>

                                Back to Home

                            </a>


                            <a
                                href="menu.php"
                                class="btn btn-warning px-4">

                                <i class="bi bi-shop me-1"></i>

                                View Menu

                            </a>


                            <?php if (
                                isset(
                                    $_SESSION["user_id"]
                                )
                            ): ?>

                                <a
                                    href="my-reservations.php"
                                    class="btn btn-dark px-4">

                                    <i
                                        class="bi bi-calendar-check me-1">
                                    </i>

                                    My Reservations

                                </a>

                            <?php endif; ?>


                        </div>


                    </div>

                </div>


            </div>

        </div>

    </div>

</section>



<!-- ========================================
     FOOTER
======================================== -->

<footer class="bg-dark text-white py-4">

    <div class="container">

        <div
            class="text-center">

            <i
                class="bi bi-egg-fried text-warning">
            </i>

            <strong>

                BiteCraft

            </strong>

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