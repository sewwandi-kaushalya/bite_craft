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

    <title>BiteCraft | Restaurant</title>

    <!-- Bootstrap CSS -->
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

</head>

<body>


<!-- ==================================================
NAVBAR
================================================== -->

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">

    <div class="container">


        <!-- LOGO -->

        <a
            class="navbar-brand fw-bold fs-3"
            href="index.php">

            <i class="bi bi-egg-fried text-warning"></i>

            Bite<span class="text-warning">Craft</span>

        </a>


        <!-- MOBILE BUTTON -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu">

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- NAVIGATION -->

        <div
            class="collapse navbar-collapse"
            id="navbarMenu">

            <ul class="navbar-nav ms-auto align-items-lg-center">


                <!-- HOME -->

                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="index.php">

                        Home

                    </a>

                </li>


                <!-- MENU -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="menu.php">

                        Menu

                    </a>

                </li>


                <!-- ABOUT -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="about.php">

                        About

                    </a>

                </li>


                <!-- CONTACT -->

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


                    <!-- ADMIN DASHBOARD -->

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


                    <!-- USER NAME -->

                    <li class="nav-item ms-lg-2">

                        <span
                            class="nav-link">

                            <i class="bi bi-person-circle"></i>

                            <?php

                            echo htmlspecialchars(
                                $_SESSION["user_name"] ?? "User"
                            );

                            ?>

                        </span>

                    </li>


                    <!-- LOGOUT -->

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
HERO SECTION
================================================== -->

<section class="hero-section">

    <div class="container">

        <div class="row align-items-center min-vh-75">


            <!-- HERO TEXT -->

            <div class="col-lg-6">

                <span
                    class="badge bg-warning text-dark px-3 py-2 mb-3">

                    <i class="bi bi-stars"></i>

                    Welcome to BiteCraft

                </span>


                <h1 class="display-3 fw-bold">

                    Delicious Food.

                    <span class="text-warning">

                        Crafted With Love.

                    </span>

                </h1>


                <p class="lead text-secondary my-4">

                    Fresh ingredients, delicious flavors
                    and unforgettable dining experiences.

                </p>


                <div class="d-flex gap-3 flex-wrap">


                    <a
                        href="menu.php"
                        class="btn btn-warning btn-lg px-4">

                        Explore Menu

                        <i class="bi bi-arrow-right"></i>

                    </a>


                    <a
                        href="reservation.php"
                        class="btn btn-outline-dark btn-lg px-4">

                        Book a Table

                    </a>


                </div>

            </div>


            <!-- HERO IMAGE -->

            <div class="col-lg-6 text-center mt-5 mt-lg-0">

                <!-- <div class="hero-circle">

                    <i class="bi bi-egg-fried"></i>

                </div> -->

                <img src="./assets/images/hero-image.png" alt="Hero Image">

            </div>

        </div>

    </div>

</section>


<!-- ==================================================
WHY CHOOSE US
================================================== -->

<section class="py-5">

    <div class="container">


        <div class="text-center mb-5">

            <span class="text-warning fw-bold">

                WHY BITECRAFT

            </span>

            <h2 class="fw-bold display-6">

                Why Choose Us?

            </h2>

            <p class="text-secondary">

                We care about every detail of your dining experience.

            </p>

        </div>


        <div class="row g-4">


            <!-- FEATURE 1 -->

            <div class="col-md-4">

                <div class="feature-card text-center p-4">

                    <div class="feature-icon">

                        <i class="bi bi-award"></i>

                    </div>

                    <h4 class="fw-bold mt-3">

                        Quality Food

                    </h4>

                    <p class="text-secondary">

                        We use fresh and high-quality
                        ingredients for every meal.

                    </p>

                </div>

            </div>


            <!-- FEATURE 2 -->

            <div class="col-md-4">

                <div class="feature-card text-center p-4">

                    <div class="feature-icon">

                        <i class="bi bi-heart"></i>

                    </div>

                    <h4 class="fw-bold mt-3">

                        Made With Love

                    </h4>

                    <p class="text-secondary">

                        Every dish is prepared with
                        passion and attention to detail.

                    </p>

                </div>

            </div>


            <!-- FEATURE 3 -->

            <div class="col-md-4">

                <div class="feature-card text-center p-4">

                    <div class="feature-icon">

                        <i class="bi bi-clock"></i>

                    </div>

                    <h4 class="fw-bold mt-3">

                        Fast Service

                    </h4>

                    <p class="text-secondary">

                        Friendly and efficient service
                        every time you visit us.

                    </p>

                </div>

            </div>


        </div>

    </div>

</section>


<!-- ==================================================
POPULAR DISHES
================================================== -->

<section class="py-5 bg-light">

    <div class="container">


        <div class="text-center mb-5">

            <span class="text-warning fw-bold">

                OUR MENU

            </span>

            <h2 class="fw-bold display-6">

                Popular Dishes

            </h2>

            <p class="text-secondary">

                Discover some of our most loved dishes.

            </p>

        </div>


        <div class="row g-4">


            <!-- BURGER -->

            <div class="col-md-6 col-lg-3">

                <div class="card food-card border-0 shadow-sm h-100">

                    <div class="food-image">

                        <img src="./assets/images/chicken_burger.png" alt="" class="img-fluid" style="max-height: 200px; object-fit: cover;">

                    </div>

                    <div class="card-body">

                        <h5 class="fw-bold">

                            Chicken Burger

                        </h5>

                        <p class="text-secondary small">

                            Juicy chicken burger with
                            fresh vegetables and special sauce.

                        </p>

                        <div
                            class="d-flex justify-content-between align-items-center">

                            <span class="fw-bold text-warning">

                                Rs. 2,500

                            </span>

                            <span>

                                ⭐ 4.8

                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- PASTA -->

            <div class="col-md-6 col-lg-3">

                <div class="card food-card border-0 shadow-sm h-100">

                    <div class="food-image">

                        <img src="./assets/images/Creamy Pasta.png" alt="" class="img-fluid" style="max-height: 200px; object-fit: cover;">

                    </div>

                    <div class="card-body">

                        <h5 class="fw-bold">

                            Creamy Pasta

                        </h5>

                        <p class="text-secondary small">

                            Creamy Italian pasta prepared
                            with fresh ingredients.

                        </p>

                        <div
                            class="d-flex justify-content-between align-items-center">

                            <span class="fw-bold text-warning">

                                Rs. 2,800

                            </span>

                            <span>

                                ⭐ 4.9

                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- PIZZA -->

            <div class="col-md-6 col-lg-3">

                <div class="card food-card border-0 shadow-sm h-100">

                    <div class="food-image">

                        <img src="./assets/images/Cheese Pizza.png" alt="" class="img-fluid" style="max-height: 200px; object-fit: cover;">

                    </div>

                    <div class="card-body">

                        <h5 class="fw-bold">

                            Cheese Pizza

                        </h5>

                        <p class="text-secondary small">

                            Crispy pizza topped with
                            mozzarella and fresh herbs.

                        </p>

                        <div
                            class="d-flex justify-content-between align-items-center">

                            <span class="fw-bold text-warning">

                                Rs. 3,500

                            </span>

                            <span>

                                ⭐ 4.7

                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- CHICKEN -->

            <div class="col-md-6 col-lg-3">

                <div class="card food-card border-0 shadow-sm h-100">

                    <div class="food-image">

                        <img src="./assets/images/Grilled Chicken.png" alt="" class="img-fluid" style="max-height: 200px; object-fit: cover;">

                    </div>

                    <div class="card-body">

                        <h5 class="fw-bold">

                            Grilled Chicken

                        </h5>

                        <p class="text-secondary small">

                            Tender grilled chicken served
                            with fresh vegetables.

                        </p>

                        <div
                            class="d-flex justify-content-between align-items-center">

                            <span class="fw-bold text-warning">

                                Rs. 4,200

                            </span>

                            <span>

                                ⭐ 4.9

                            </span>

                        </div>

                    </div>

                </div>

            </div>


        </div>


        <div class="text-center mt-5">

            <a
                href="menu.php"
                class="btn btn-dark px-4">

                View Full Menu

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </div>

</section>


<!-- ==================================================
ABOUT SECTION
================================================== -->

<section class="py-5">

    <div class="container">

        <div class="row align-items-center g-5">


            <div class="col-lg-6">

                <div class="">

                   <img src="./assets/images/about.png" alt="" class="img-fluid" style="object-fit: cover;">

                </div>

            </div>


            <div class="col-lg-6">

                <span class="text-warning fw-bold">

                    ABOUT US

                </span>

                <h2 class="display-6 fw-bold mt-2">

                    Food That Brings
                    People Together

                </h2>

                <p class="text-secondary mt-3">

                    At BiteCraft, we believe great food
                    creates great memories.

                </p>

                <p class="text-secondary">

                    Our chefs carefully prepare every dish
                    using fresh ingredients and delicious
                    flavors to give you an unforgettable
                    dining experience.

                </p>

                <a
                    href="about.php"
                    class="btn btn-outline-dark mt-3">

                    Learn More

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>

    </div>

</section>


<!-- ==================================================
RESERVATION CTA
================================================== -->

<section class="reservation-section py-5">

    <div class="container">

        <div class="reservation-box text-center p-5">

            <i class="bi bi-calendar-heart display-4"></i>

            <h2 class="fw-bold mt-3">

                Reserve Your Table

            </h2>

            <p class="mb-4">

                Make your next meal special.
                Reserve your table today.

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


            <!-- QUICK LINKS -->

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


            <!-- CONTACT -->

            <div class="col-lg-4">

                <h6 class="fw-bold">

                    Contact Us

                </h6>

                <p class="text-white opacity-75">

                    <i class="bi bi-geo-alt text-warning"></i>

                    Colombo, Sri Lanka

                </p>

                <p class="text-white opacity-75">

                    <i class="bi bi-telephone text-warning"></i>

                    +94 77 123 4567

                </p>

                <p class="text-white opacity-75">

                    <i class="bi bi-envelope text-warning"></i>

                    hello@bitecraft.com

                </p>

            </div>


        </div>


        <hr class="border-secondary mt-4">


        <div class="text-center">

            © 2026 BiteCraft Restaurant.
            All Rights Reserved.

        </div>

    </div>

</footer>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>