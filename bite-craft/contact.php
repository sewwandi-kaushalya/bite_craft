
<?php

session_start();

require_once "config/database.php";

$success = "";
$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");


    if (
        empty($name) ||
        empty($email) ||
        empty($subject) ||
        empty($message)
    ) {

        $error = "Please fill in all fields.";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    }

    else {

        /*
         * If you have a messages table,
         * save the message here.
         *
         * For now, show success message.
         */

        $success =
            "Thank you! Your message has been sent successfully.";

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

    <title>Contact Us | BiteCraft</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <link
        rel="stylesheet"
        href="assets/css/style.css">


    <style>

        body {
            background-color: #f8f9fa;
        }


        /* HERO */

        .contact-hero {

            min-height: 350px;

            background:
                linear-gradient(
                    rgba(0,0,0,0.65),
                    rgba(0,0,0,0.65)
                ),
                url("./assets/images/contact-hero.png")
                center/cover no-repeat;

            display: flex;

            align-items: center;

            color: white;
        }


        .contact-hero h1 {

            font-size: 55px;
        }


        /* INFO CARD */

        .contact-info {

            background-color: white;

            border-radius: 15px;

            padding: 30px;

            height: 100%;

            box-shadow:
                0 5px 20px
                rgba(0,0,0,0.06);
        }


        .contact-icon {

            width: 55px;

            height: 55px;

            background-color: #fff3cd;

            color: #ffc107;

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 24px;
        }


        /* FORM */

        .contact-form {

            background-color: white;

            border-radius: 15px;

            padding: 35px;

            box-shadow:
                0 5px 20px
                rgba(0,0,0,0.06);
        }


        .form-control {

            padding: 12px 15px;

            border-radius: 10px;
        }


        .form-control:focus {

            border-color: #ffc107;

            box-shadow:
                0 0 0 0.2rem
                rgba(255,193,7,0.15);
        }


        /* HOURS */

        .hours-card {

            background-color: #212529;

            color: white;

            border-radius: 15px;

            padding: 30px;
        }


        .hours-card h4 {

            color: #ffc107;
        }


        /* FOOTER */

        .footer-link {

            display: block;

            color: #adb5bd;

            text-decoration: none;

            margin-bottom: 8px;
        }


        .footer-link:hover {

            color: #ffc107;
        }


        .social-icons a {

            color: white;

            font-size: 20px;

            margin-right: 15px;

            text-decoration: none;
        }


        .social-icons a:hover {

            color: #ffc107;
        }


        @media (max-width: 768px) {

            .contact-hero h1 {

                font-size: 40px;
            }

        }

    </style>

</head>


<body>


<!-- ==================================================
NAVBAR
================================================== -->

<!-- ==================================================
     COMMON NAVBAR
================================================== -->

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">

    <div class="container">


        <!-- ==================================================
             LOGO
        ================================================== -->

        <a
            class="navbar-brand fw-bold fs-3"
            href="index.php">

            <i class="bi bi-egg-fried text-warning"></i>

            Bite<span class="text-warning">Craft</span>

        </a>


        <!-- ==================================================
             MOBILE MENU BUTTON
        ================================================== -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
            aria-controls="navbarMenu"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- ==================================================
             NAVIGATION MENU
        ================================================== -->

        <div
            class="collapse navbar-collapse"
            id="navbarMenu">

            <ul class="navbar-nav ms-auto align-items-lg-center">


                <!-- ==================================================
                     HOME
                ================================================== -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php">

                        Home

                    </a>

                </li>


                <!-- ==================================================
                     MENU
                ================================================== -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="menu.php">

                        Menu

                    </a>

                </li>


                <!-- ==================================================
                     ABOUT
                ================================================== -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="about.php">

                        About

                    </a>

                </li>


                <!-- ==================================================
                     CONTACT
                ================================================== -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="contact.php">

                        Contact

                    </a>

                </li>


                <!-- ==================================================
                     LOGGED-IN USER
                ================================================== -->

                <?php if (isset($_SESSION["user_id"])): ?>


                    <!-- MY ORDERS -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="my-orders.php">

                            <i class="bi bi-receipt"></i>

                            My Orders

                        </a>

                    </li>


                    <!-- ==================================================
                         ADMIN DASHBOARD
                    ================================================== -->

                    <?php if (
                        isset($_SESSION["user_role"]) &&
                        $_SESSION["user_role"] === "admin"
                    ): ?>

                        <li class="nav-item">

                            <a
                                class="nav-link text-danger fw-semibold"
                                href="admin/index.php">

                                <i class="bi bi-speedometer2"></i>

                                Admin Dashboard

                            </a>

                        </li>

                    <?php endif; ?>


                    <!-- ==================================================
                         USER NAME
                    ================================================== -->

                    <li class="nav-item ms-lg-2">

                        <span class="nav-link">

                            <i class="bi bi-person-circle"></i>

                            <?php

                            echo htmlspecialchars(
                                $_SESSION["user_name"] ?? "User"
                            );

                            ?>

                        </span>

                    </li>


                    <!-- ==================================================
                         LOGOUT
                    ================================================== -->

                    <li class="nav-item ms-lg-2">

                        <a
                            href="logout.php"
                            class="btn btn-outline-dark px-3">

                            <i class="bi bi-box-arrow-right"></i>

                            Logout

                        </a>

                    </li>


                <?php else: ?>


                    <!-- ==================================================
                         GUEST USER
                    ================================================== -->


                    <!-- LOGIN -->

                    <li class="nav-item ms-lg-2">

                        <a
                            href="login.php"
                            class="btn btn-outline-dark px-3">

                            <i class="bi bi-box-arrow-in-right"></i>

                            Login

                        </a>

                    </li>


                    <!-- REGISTER -->

                    <li class="nav-item ms-lg-2">

                        <a
                            href="register.php"
                            class="btn btn-dark px-3">

                            <i class="bi bi-person-plus"></i>

                            Register

                        </a>

                    </li>


                <?php endif; ?>


                <!-- ==================================================
                     BOOK A TABLE
                ================================================== -->

                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">

                    <a
                        href="reservation.php"
                        class="btn btn-warning px-4">

                        <i class="bi bi-calendar-check"></i>

                        Book a Table

                    </a>

                </li>


            </ul>

        </div>

    </div>

</nav>


<!-- ==================================================
HERO
================================================== -->

<section class="contact-hero">

    <div class="container">

        <span class="badge bg-warning text-dark px-3 py-2 mb-3">

            GET IN TOUCH

        </span>

        <h1 class="fw-bold">

            Contact <span class="text-warning">Us</span>

        </h1>

        <p class="lead">

            We'd love to hear from you.

        </p>

    </div>

</section>


<!-- ==================================================
CONTACT CONTENT
================================================== -->

<section class="py-5">

    <div class="container">

        <div class="row g-4">


            <!-- ========================================
                 CONTACT INFO
            ======================================== -->

            <div class="col-lg-5">


                <div class="contact-info">

                    <span class="text-warning fw-bold">

                        CONTACT INFORMATION

                    </span>


                    <h2 class="fw-bold mt-2">

                        Let's Talk

                    </h2>


                    <p class="text-secondary">

                        Have a question, feedback or special
                        request? Send us a message or visit us.

                    </p>


                    <!-- LOCATION -->

                    <div class="d-flex gap-3 mt-4">

                        <div class="contact-icon">

                            <i class="bi bi-geo-alt"></i>

                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">

                                Our Location

                            </h6>

                            <p class="text-secondary mb-0">

                                Colombo, Sri Lanka

                            </p>

                        </div>

                    </div>


                    <!-- PHONE -->

                    <div class="d-flex gap-3 mt-4">

                        <div class="contact-icon">

                            <i class="bi bi-telephone"></i>

                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">

                                Phone

                            </h6>

                            <p class="text-secondary mb-0">

                                +94 77 123 4567

                            </p>

                        </div>

                    </div>


                    <!-- EMAIL -->

                    <div class="d-flex gap-3 mt-4">

                        <div class="contact-icon">

                            <i class="bi bi-envelope"></i>

                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">

                                Email

                            </h6>

                            <p class="text-secondary mb-0">

                                hello@bitecraft.com

                            </p>

                        </div>

                    </div>


                    <!-- HOURS -->

                    <div class="hours-card mt-4">

                        <h4 class="fw-bold">

                            <i class="bi bi-clock"></i>

                            Opening Hours

                        </h4>

                        <hr class="border-secondary">

                        <div class="d-flex justify-content-between">

                            <span>Monday - Friday</span>

                            <strong>10:00 AM - 10:00 PM</strong>

                        </div>

                        <div class="d-flex justify-content-between mt-3">

                            <span>Saturday - Sunday</span>

                            <strong>9:00 AM - 11:00 PM</strong>

                        </div>

                    </div>


                </div>

            </div>


            <!-- ========================================
                 CONTACT FORM
            ======================================== -->

            <div class="col-lg-7">


                <div class="contact-form">


                    <h4 class="fw-bold">

                        Send Us a Message

                    </h4>


                    <p class="text-secondary">

                        Fill out the form below and we'll
                        get back to you as soon as possible.

                    </p>


                    <!-- ERROR -->

                    <?php if ($error): ?>

                        <div class="alert alert-danger">

                            <i class="bi bi-exclamation-circle"></i>

                            <?php

                            echo htmlspecialchars($error);

                            ?>

                        </div>

                    <?php endif; ?>


                    <!-- SUCCESS -->

                    <?php if ($success): ?>

                        <div class="alert alert-success">

                            <i class="bi bi-check-circle"></i>

                            <?php

                            echo htmlspecialchars($success);

                            ?>

                        </div>

                    <?php endif; ?>


                    <form method="POST">


                        <!-- NAME -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Your Name

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Enter your name"
                                required>

                        </div>


                        <!-- EMAIL -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Email Address

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter your email"
                                required>

                        </div>


                        <!-- SUBJECT -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Subject

                            </label>

                            <input
                                type="text"
                                name="subject"
                                class="form-control"
                                placeholder="What is this about?"
                                required>

                        </div>


                        <!-- MESSAGE -->

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Message

                            </label>

                            <textarea
                                name="message"
                                class="form-control"
                                rows="6"
                                placeholder="Write your message..."
                                required></textarea>

                        </div>


                        <!-- BUTTON -->

                        <button
                            type="submit"
                            class="btn btn-warning px-5 py-2">

                            <i class="bi bi-send"></i>

                            Send Message

                        </button>


                    </form>


                </div>

            </div>


        </div>

    </div>

</section>


<!-- ==================================================
MAP / CTA
================================================== -->

<section class="py-5 bg-white">

    <div class="container">

        <div class="text-center">

            <span class="text-warning fw-bold">

                VISIT US

            </span>

            <h2 class="fw-bold mt-2">

                Come Dine With Us

            </h2>

            <p class="text-secondary">

                Great food and great memories are waiting for you.

            </p>


            <a
                href="reservation.php"
                class="btn btn-dark btn-lg px-5 mt-2">

                <i class="bi bi-calendar-check"></i>

                Book a Table

            </a>

        </div>

    </div>

</section>


<!-- ==================================================
FOOTER
================================================== -->


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




<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>

