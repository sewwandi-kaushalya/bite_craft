
<?php

session_start();

require_once "config/database.php";

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>About Us | BiteCraft</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <!-- Custom CSS -->

    <link
        rel="stylesheet"
        href="assets/css/style.css">


    <style>

        body {
            background-color: #ffffff;
        }

        /* HERO */

        .about-hero {

            min-height: 430px;

            background:
                linear-gradient(
                    rgba(0,0,0,0.55),
                    rgba(0,0,0,0.55)
                ),
                url("./assets/images/about-hero.png")
                center/cover no-repeat;

            display: flex;

            align-items: center;

            color: white;
        }


        .about-hero h1 {
            font-size: 55px;
        }


        /* IMAGE */

        .about-image {

            min-height: 420px;

            background:
                url("./assets/images/about-restaurant.png")
                center/cover no-repeat;

            border-radius: 20px;

            box-shadow:
                0 15px 40px
                rgba(0,0,0,0.12);
        }


        /* FEATURE */

        .about-feature {

            background-color: #ffffff;

            border-radius: 15px;

            padding: 30px;

            height: 100%;

            box-shadow:
                0 5px 20px
                rgba(0,0,0,0.06);

            transition: 0.3s;
        }


        .about-feature:hover {

            transform: translateY(-6px);
        }


        .about-feature-icon {

            width: 60px;

            height: 60px;

            border-radius: 15px;

            background-color: #fff3cd;

            color: #ffc107;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 28px;
        }


        /* STATS */

        .about-stat {

            background-color: #212529;

            color: white;

            border-radius: 15px;

            padding: 30px;

            text-align: center;
        }


        .about-stat h2 {

            color: #ffc107;

            font-size: 40px;

            font-weight: bold;
        }


        /* CTA */

        .about-cta {

            background-color: #ffc107;

            border-radius: 20px;
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

            .about-hero h1 {

                font-size: 40px;
            }

            .about-image {

                min-height: 300px;
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

<section class="about-hero">

    <div class="container">

        <div class="row">

            <div class="col-lg-7">

                <span class="badge bg-warning text-dark px-3 py-2 mb-3">

                    ABOUT BITECRAFT

                </span>

                <h1 class="fw-bold">

                    Food That Brings
                    <span class="text-warning">

                        People Together

                    </span>

                </h1>

                <p class="lead mt-3">

                    Great food, warm hospitality and
                    unforgettable moments.

                </p>

            </div>

        </div>

    </div>

</section>


<!-- ==================================================
OUR STORY
================================================== -->

<section class="py-5">

    <div class="container">

        <div class="row align-items-center g-5">


            <div class="col-lg-6">

                <div class="about-image"></div>

            </div>


            <div class="col-lg-6">

                <span class="text-warning fw-bold">

                    OUR STORY

                </span>

                <h2 class="display-6 fw-bold mt-2">

                    Welcome to BiteCraft

                </h2>

                <p class="text-secondary mt-3">

                    BiteCraft is a modern restaurant created
                    for people who love delicious food and
                    memorable dining experiences.

                </p>

                <p class="text-secondary">

                    From carefully selected ingredients to
                    beautifully prepared dishes, we focus on
                    quality, freshness and great taste.

                </p>

                <p class="text-secondary">

                    Whether you're enjoying a casual meal
                    with friends, having dinner with family,
                    or celebrating a special moment,
                    BiteCraft is here to make it memorable.

                </p>

            </div>


        </div>

    </div>

</section>


<!-- ==================================================
OUR VALUES
================================================== -->

<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <span class="text-warning fw-bold">

                WHAT WE BELIEVE

            </span>

            <h2 class="fw-bold display-6">

                Our Values

            </h2>

            <p class="text-secondary">

                The values behind every BiteCraft experience.

            </p>

        </div>


        <div class="row g-4">


            <!-- QUALITY -->

            <div class="col-md-4">

                <div class="about-feature">

                    <div class="about-feature-icon">

                        <i class="bi bi-award"></i>

                    </div>

                    <h4 class="fw-bold mt-4">

                        Quality First

                    </h4>

                    <p class="text-secondary">

                        We carefully select fresh,
                        high-quality ingredients to
                        create delicious meals.

                    </p>

                </div>

            </div>


            <!-- PASSION -->

            <div class="col-md-4">

                <div class="about-feature">

                    <div class="about-feature-icon">

                        <i class="bi bi-heart"></i>

                    </div>

                    <h4 class="fw-bold mt-4">

                        Made With Passion

                    </h4>

                    <p class="text-secondary">

                        Our dishes are prepared with
                        passion, creativity and attention
                        to every detail.

                    </p>

                </div>

            </div>


            <!-- SERVICE -->

            <div class="col-md-4">

                <div class="about-feature">

                    <div class="about-feature-icon">

                        <i class="bi bi-people"></i>

                    </div>

                    <h4 class="fw-bold mt-4">

                        Great Hospitality

                    </h4>

                    <p class="text-secondary">

                        We believe friendly service is
                        an important part of a great
                        dining experience.

                    </p>

                </div>

            </div>


        </div>

    </div>

</section>


<!-- ==================================================
STATS
================================================== -->

<section class="py-5">

    <div class="container">

        <div class="row g-4">


            <div class="col-md-3">

                <div class="about-stat">

                    <h2>10+</h2>

                    <p class="mb-0">

                        Years Experience

                    </p>

                </div>

            </div>


            <div class="col-md-3">

                <div class="about-stat">

                    <h2>50+</h2>

                    <p class="mb-0">

                        Menu Items

                    </p>

                </div>

            </div>


            <div class="col-md-3">

                <div class="about-stat">

                    <h2>10K+</h2>

                    <p class="mb-0">

                        Happy Customers

                    </p>

                </div>

            </div>


            <div class="col-md-3">

                <div class="about-stat">

                    <h2>4.9</h2>

                    <p class="mb-0">

                        Customer Rating

                    </p>

                </div>

            </div>


        </div>

    </div>

</section>


<!-- ==================================================
CTA
================================================== -->

<section class="py-5">

    <div class="container">

        <div class="about-cta text-center p-5">

            <i class="bi bi-calendar-heart display-4"></i>

            <h2 class="fw-bold mt-3">

                Ready for a Great Meal?

            </h2>

            <p>

                Come and experience BiteCraft for yourself.

            </p>

            <a
                href="reservation.php"
                class="btn btn-dark btn-lg px-5">

                Book a Table

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </div>

</section>


<!-- ==================================================
FOOTER
================================================== -->

<footer class="bg-dark text-white py-5">

    <div class="container">

        <div class="row g-4">


            <div class="col-lg-5">

                <h4 class="fw-bold">

                    <i class="bi bi-egg-fried text-warning"></i>

                    BiteCraft

                </h4>

                <p class="text-secondary">

                    Delicious food.
                    Memorable moments.

                </p>

                <div class="social-icons mt-3">

                    <a href="#">

                        <i class="bi bi-facebook"></i>

                    </a>

                    <a href="#">

                        <i class="bi bi-instagram"></i>

                    </a>

                    <a href="#">

                        <i class="bi bi-whatsapp"></i>

                    </a>

                </div>

            </div>


            <div class="col-lg-3">

                <h6 class="fw-bold">

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

            </div>


            <div class="col-lg-4">

                <h6 class="fw-bold">

                    Contact Us

                </h6>

                <p class="text-secondary">

                    <i class="bi bi-geo-alt text-warning"></i>

                    Colombo, Sri Lanka

                </p>

                <p class="text-secondary">

                    <i class="bi bi-telephone text-warning"></i>

                    +94 77 123 4567

                </p>

                <p class="text-secondary">

                    <i class="bi bi-envelope text-warning"></i>

                    hello@bitecraft.com

                </p>

            </div>


        </div>


        <hr class="border-secondary mt-4">


        <div class="text-center text-secondary">

            © 2026 BiteCraft Restaurant.
            All Rights Reserved.

        </div>

    </div>

</footer>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>

