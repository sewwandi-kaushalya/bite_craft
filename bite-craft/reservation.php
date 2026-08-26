
<?php

session_start();

require_once "config/database.php";

// ========================================
// VARIABLES
// ========================================

$success = "";
$error = "";


// ========================================
// FORM SUBMISSION
// ========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $customer_name = trim($_POST["customer_name"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $reservation_date = $_POST["reservation_date"] ?? "";
    $reservation_time = $_POST["reservation_time"] ?? "";
    $guests = (int) ($_POST["guests"] ?? 0);
    $special_request = trim($_POST["special_request"] ?? "");


    // ========================================
    // VALIDATION
    // ========================================

    if (
        $customer_name === "" ||
        $phone === "" ||
        $reservation_date === "" ||
        $reservation_time === "" ||
        $guests <= 0
    ) {

        $error = "Please fill in all required fields.";

    } elseif (strlen($customer_name) < 2) {

        $error = "Please enter a valid customer name.";

    } elseif (!preg_match("/^[0-9+\-\s]{7,20}$/", $phone)) {

        $error = "Please enter a valid phone number.";

    } elseif (
        $email !== "" &&
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {

        $error = "Please enter a valid email address.";

    } elseif ($guests > 20) {

        $error = "Maximum 20 guests can be reserved at once.";

    } else {


        // ========================================
        // CHECK DATE
        // ========================================

        if ($reservation_date < date("Y-m-d")) {

            $error = "Please select a future date.";

        } else {


            // ========================================
            // CHECK TIME
            // ========================================

            if (
                $reservation_time < "10:00" ||
                $reservation_time > "22:00"
            ) {

                $error = "Please select a time between 10:00 AM and 10:00 PM.";

            } else {


                // ========================================
                // CHECK ALREADY BOOKED TIME
                // ========================================

                $check = $conn->prepare(
                    "SELECT id
                     FROM reservations
                     WHERE reservation_date = ?
                     AND reservation_time = ?
                     AND status IN ('pending', 'confirmed')
                     LIMIT 1"
                );


                $check->bind_param(
                    "ss",
                    $reservation_date,
                    $reservation_time
                );


                $check->execute();


                $check_result =
                    $check->get_result();


                if ($check_result->num_rows > 0) {

                    $error =
                        "Sorry, this time slot is already reserved. Please choose another time.";

                    $check->close();

                } else {

                    $check->close();


                    // ========================================
                    // INSERT RESERVATION
                    // DATABASE COLUMNS:
                    // name
                    // email
                    // phone
                    // guests
                    // reservation_date
                    // reservation_time
                    // special_request
                    // status
                    // ========================================

                    $stmt = $conn->prepare(
                        "INSERT INTO reservations
                        (
                            name,
                            email,
                            phone,
                            guests,
                            reservation_date,
                            reservation_time,
                            special_request,
                            status
                        )
                        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')"
                    );


                    $stmt->bind_param(
                        "sssisss",
                        $customer_name,
                        $email,
                        $phone,
                        $guests,
                        $reservation_date,
                        $reservation_time,
                        $special_request
                    );


                    if ($stmt->execute()) {

                        $reservation_id =
                            $stmt->insert_id;


                        $stmt->close();


                        // ========================================
                        // SAVE RESERVATION ID
                        // ========================================

                        $_SESSION["last_reservation_id"] =
                            $reservation_id;


                        // ========================================
                        // REDIRECT SUCCESS PAGE
                        // ========================================

                        header(
                            "Location: reservation-success.php"
                        );

                        exit;

                    } else {

                        $error =
                            "Something went wrong. Please try again.";

                        $stmt->close();
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Reserve a Table | BiteCraft
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


        .reservation-section {

            min-height: 85vh;

            padding: 70px 0;

        }


        .reservation-card {

            background-color: white;

            border: none;

            border-radius: 20px;

            box-shadow:
                0 10px 35px
                rgba(0, 0, 0, 0.08);

        }


        .reservation-icon {

            width: 75px;

            height: 75px;

            border-radius: 50%;

            background-color: #fff3cd;

            display: flex;

            align-items: center;

            justify-content: center;

            margin: 0 auto;

            font-size: 35px;

            color: #ffc107;

        }


        .form-control,
        .form-select {

            padding: 12px 15px;

            border-radius: 10px;

        }


        .form-control:focus,
        .form-select:focus {

            border-color: #ffc107;

            box-shadow:
                0 0 0 0.2rem
                rgba(255, 193, 7, 0.15);

        }


        .btn-warning {

            font-weight: 600;

        }


        .info-card {

            background-color: #212529;

            color: white;

            border-radius: 20px;

            height: 100%;

        }


        .info-item {

            display: flex;

            gap: 15px;

            margin-bottom: 25px;

        }


        .info-item i {

            color: #ffc107;

            font-size: 22px;

        }


        footer {

            margin-top: 0;

        }

    </style>

</head>


<body>


<!-- ========================================
     NAVBAR
======================================== -->

<nav
    class="navbar navbar-light bg-white shadow-sm sticky-top">

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
     RESERVATION SECTION
======================================== -->

<section class="reservation-section">

    <div class="container">


        <!-- PAGE HEADER -->

        <div class="text-center mb-5">

            <span
                class="text-warning fw-bold">

                BITECRAFT

            </span>


            <h1 class="fw-bold display-5 mt-2">

                Reserve Your Table

            </h1>


            <p class="text-secondary">

                Make your dining experience special.
                Book your table with us today.

            </p>

        </div>


        <div class="row g-4 justify-content-center">


            <!-- ====================================
                 INFORMATION
            ===================================== -->

            <div class="col-lg-4">

                <div class="info-card p-4">


                    <div
                        class="reservation-icon mb-4">

                        <i
                            class="bi bi-calendar-heart">
                        </i>

                    </div>


                    <h3
                        class="fw-bold text-center mb-4">

                        Dining at BiteCraft

                    </h3>


                    <p
                        class="text-light-emphasis mb-4">

                        Reserve your table in advance
                        and enjoy delicious food with
                        your friends and family.

                    </p>


                    <!-- OPENING HOURS -->

                    <div class="info-item">

                        <i
                            class="bi bi-clock">
                        </i>

                        <div>

                            <strong>
                                Opening Hours
                            </strong>

                            <p
                                class="text-light-emphasis mb-0">

                                Monday - Sunday

                                <br>

                                10:00 AM - 10:00 PM

                            </p>

                        </div>

                    </div>


                    <!-- LOCATION -->

                    <div class="info-item">

                        <i
                            class="bi bi-geo-alt">
                        </i>

                        <div>

                            <strong>
                                Location
                            </strong>

                            <p
                                class="text-light-emphasis mb-0">

                                Colombo, Sri Lanka

                            </p>

                        </div>

                    </div>


                    <!-- PHONE -->

                    <div class="info-item">

                        <i
                            class="bi bi-telephone">
                        </i>

                        <div>

                            <strong>
                                Phone
                            </strong>

                            <p
                                class="text-light-emphasis mb-0">

                                +94 77 123 4567

                            </p>

                        </div>

                    </div>


                    <!-- EMAIL -->

                    <div
                        class="info-item mb-0">

                        <i
                            class="bi bi-envelope">
                        </i>

                        <div>

                            <strong>
                                Email
                            </strong>

                            <p
                                class="text-light-emphasis mb-0">

                                hello@bitecraft.com

                            </p>

                        </div>

                    </div>


                </div>

            </div>


            <!-- ====================================
                 RESERVATION FORM
            ===================================== -->

            <div class="col-lg-7">

                <div
                    class="card reservation-card p-4 p-md-5">


                    <div
                        class="d-flex align-items-center mb-4">

                        <i
                            class="bi bi-calendar-check text-warning fs-2 me-3">
                        </i>

                        <div>

                            <h3
                                class="fw-bold mb-0">

                                Book a Table

                            </h3>

                            <small
                                class="text-secondary">

                                Fill in your reservation details.

                            </small>

                        </div>

                    </div>


                    <!-- ERROR -->

                    <?php if ($error !== ""): ?>

                        <div
                            class="alert alert-danger">

                            <i
                                class="bi bi-exclamation-circle me-2">
                            </i>

                            <?php

                            echo htmlspecialchars(
                                $error
                            );

                            ?>

                        </div>

                    <?php endif; ?>


                    <!-- FORM -->

                    <form
                        method="POST"
                        action="">


                        <div class="row g-3">


                            <!-- CUSTOMER NAME -->

                            <div class="col-md-6">

                                <label
                                    class="form-label fw-semibold">

                                    Customer Name

                                    <span
                                        class="text-danger">

                                        *

                                    </span>

                                </label>


                                <input
                                    type="text"
                                    name="customer_name"
                                    class="form-control"
                                    placeholder="Enter your name"
                                    value="<?php

                                    echo htmlspecialchars(
                                        $_POST[
                                            "customer_name"
                                        ] ?? ""
                                    );

                                    ?>"
                                    required>

                            </div>


                            <!-- PHONE -->

                            <div class="col-md-6">

                                <label
                                    class="form-label fw-semibold">

                                    Phone Number

                                    <span
                                        class="text-danger">

                                        *

                                    </span>

                                </label>


                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    placeholder="+94 77 123 4567"
                                    value="<?php

                                    echo htmlspecialchars(
                                        $_POST[
                                            "phone"
                                        ] ?? ""
                                    );

                                    ?>"
                                    required>

                            </div>


                            <!-- EMAIL -->

                            <div class="col-12">

                                <label
                                    class="form-label fw-semibold">

                                    Email Address

                                </label>


                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="your@email.com"
                                    value="<?php

                                    echo htmlspecialchars(
                                        $_POST[
                                            "email"
                                        ] ?? ""
                                    );

                                    ?>">

                            </div>


                            <!-- DATE -->

                            <div class="col-md-6">

                                <label
                                    class="form-label fw-semibold">

                                    Reservation Date

                                    <span
                                        class="text-danger">

                                        *

                                    </span>

                                </label>


                                <input
                                    type="date"
                                    name="reservation_date"
                                    class="form-control"
                                    min="<?php
                                        echo date("Y-m-d");
                                    ?>"
                                    value="<?php

                                    echo htmlspecialchars(
                                        $_POST[
                                            "reservation_date"
                                        ] ?? ""
                                    );

                                    ?>"
                                    required>

                            </div>


                            <!-- TIME -->

                            <div class="col-md-6">

                                <label
                                    class="form-label fw-semibold">

                                    Reservation Time

                                    <span
                                        class="text-danger">

                                        *

                                    </span>

                                </label>


                                <input
                                    type="time"
                                    name="reservation_time"
                                    class="form-control"
                                    min="10:00"
                                    max="22:00"
                                    value="<?php

                                    echo htmlspecialchars(
                                        $_POST[
                                            "reservation_time"
                                        ] ?? ""
                                    );

                                    ?>"
                                    required>

                            </div>


                            <!-- GUESTS -->

                            <div class="col-md-6">

                                <label
                                    class="form-label fw-semibold">

                                    Number of Guests

                                    <span
                                        class="text-danger">

                                        *

                                    </span>

                                </label>


                                <select
                                    name="guests"
                                    class="form-select"
                                    required>

                                    <option value="">

                                        Select guests

                                    </option>


                                    <?php for (
                                        $i = 1;
                                        $i <= 20;
                                        $i++
                                    ): ?>

                                        <option
                                            value="<?php
                                                echo $i;
                                            ?>"
                                            <?php

                                            echo (
                                                isset(
                                                    $_POST[
                                                        "guests"
                                                    ]
                                                ) &&
                                                (int)
                                                $_POST[
                                                    "guests"
                                                ] === $i
                                            )
                                                ? "selected"
                                                : "";

                                            ?>>

                                            <?php
                                            echo $i;
                                            ?>

                                            <?php
                                            echo $i === 1
                                                ? " Guest"
                                                : " Guests";
                                            ?>

                                        </option>

                                    <?php endfor; ?>

                                </select>

                            </div>


                            <!-- SPECIAL REQUEST -->

                            <div class="col-12">

                                <label
                                    class="form-label fw-semibold">

                                    Special Request

                                </label>


                                <textarea
                                    name="special_request"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Any special requests or requirements?"><?php

                                    echo htmlspecialchars(
                                        $_POST[
                                            "special_request"
                                        ] ?? ""
                                    );

                                    ?></textarea>

                            </div>


                            <!-- NOTE -->

                            <div class="col-12">

                                <div
                                    class="alert alert-light border mb-0">

                                    <i
                                        class="bi bi-info-circle text-warning me-2">
                                    </i>

                                    Your reservation will be

                                    <strong>
                                        Pending
                                    </strong>

                                    until it is confirmed by
                                    BiteCraft.

                                </div>

                            </div>


                            <!-- BUTTON -->

                            <div
                                class="col-12 mt-4">

                                <button
                                    type="submit"
                                    class="btn btn-warning btn-lg w-100">

                                    <i
                                        class="bi bi-calendar-check me-2">
                                    </i>

                                    Book Table

                                </button>

                            </div>


                        </div>

                    </form>


                </div>

            </div>


        </div>

    </div>

</section>


<!-- ========================================
     FOOTER
======================================== -->


<!-- ==================================================
     COMMON FOOTER
================================================== -->

<footer class="bg-dark text-white py-5">

    <div class="container">

        <div class="row g-4">

            <!-- BRAND -->
            <div class="col-lg-5">

                <h4 class="fw-bold">

                    <i class="bi bi-egg-fried text-warning"></i>

                    BiteCraft

                </h4>

                <p class="text-white opacity-75">

                    Delicious food.
                    Memorable moments.

                </p>

                <!-- SOCIAL ICONS -->

                <div class="social-icons mt-3">

                    <a
                        href="#"
                        class="text-white me-3 text-decoration-none">

                        <i class="bi bi-facebook"></i>

                    </a>

                    <a
                        href="#"
                        class="text-white me-3 text-decoration-none">

                        <i class="bi bi-instagram"></i>

                    </a>

                    <a
                        href="#"
                        class="text-white text-decoration-none">

                        <i class="bi bi-whatsapp"></i>

                    </a>

                </div>

            </div>


            <!-- QUICK LINKS -->

            <div class="col-lg-3">

                <h6 class="fw-bold mb-3">

                    Quick Links

                </h6>


                <a
                    href="index.php"
                    class="footer-link">

                    Home

                </a>


                <a
                    href="menu.php"
                    class="footer-link">

                    Menu

                </a>


                <a
                    href="about.php"
                    class="footer-link">

                    About

                </a>


                <a
                    href="contact.php"
                    class="footer-link">

                    Contact

                </a>


                <?php if (isset($_SESSION["user_id"])): ?>

                    <a
                        href="my-orders.php"
                        class="footer-link">

                        My Orders

                    </a>

                <?php endif; ?>


                <?php if (
                    isset($_SESSION["user_role"]) &&
                    $_SESSION["user_role"] === "admin"
                ): ?>

                    <a
                        href="admin/index.php"
                        class="footer-link">

                        Admin Dashboard

                    </a>

                <?php endif; ?>

            </div>


            <!-- CONTACT -->

            <div class="col-lg-4">

                <h6 class="fw-bold mb-3">

                    Contact Us

                </h6>


                <p class="text-white opacity-75 mb-2">

                    <i class="bi bi-geo-alt text-warning"></i>

                    Colombo, Sri Lanka

                </p>


                <p class="text-white opacity-75 mb-2">

                    <i class="bi bi-telephone text-warning"></i>

                    +94 77 123 4567

                </p>


                <p class="text-white opacity-75 mb-2">

                    <i class="bi bi-envelope text-warning"></i>

                    hello@bitecraft.com

                </p>


                <p class="text-white opacity-75">

                    <i class="bi bi-clock text-warning"></i>

                    Mon - Sun: 9:00 AM - 11:00 PM

                </p>

            </div>

        </div>


        <hr class="border-secondary mt-4">


        <!-- COPYRIGHT -->

        <div class="text-center">

            <p class="text-white opacity-75 mb-0">

                © <?php echo date("Y"); ?>

                BiteCraft Restaurant.

                All Rights Reserved.

            </p>

        </div>

    </div>

</footer>


<!-- ==================================================
     COMMON FOOTER CSS
================================================== -->

<style>

    .footer-link {

        display: block;

        color: rgba(255, 255, 255, 0.65);

        text-decoration: none;

        margin-bottom: 10px;

        transition: 0.3s;

    }


    .footer-link:hover {

        color: #ffc107;

        padding-left: 5px;

    }


    .social-icons a {

        font-size: 21px;

        transition: 0.3s;

    }


    .social-icons a:hover {

        color: #ffc107 !important;

    }

</style>




<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>

