<?php

session_start();

require_once "config/database.php";


// ========================================
// GET AVAILABLE MENU ITEMS
// ========================================

$sql = "SELECT
            menu_items.*,
            categories.name AS category_name
        FROM menu_items
        LEFT JOIN categories
        ON menu_items.category_id = categories.id
        WHERE menu_items.status = 'available'
        ORDER BY menu_items.id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Menu | BiteCraft</title>


    <!-- ========================================
         BOOTSTRAP CSS
    ========================================= -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- ========================================
         BOOTSTRAP ICONS
    ========================================= -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <!-- ========================================
         CUSTOM CSS
    ========================================= -->

    <style>

        body {

            background-color: #f8f9fa;

            font-family: Arial, sans-serif;

        }


        /* ================================
           NAVBAR
        ================================= */

        .navbar {

            background-color: white;

        }


        .navbar-brand {

            font-size: 25px;

        }


        .navbar-brand span {

            color: #ffc107;

        }


        .navbar .nav-link {

            color: #212529;

            font-weight: 500;

            margin-left: 8px;

        }


        .navbar .nav-link:hover {

            color: #ffc107;

        }


        .navbar .nav-link.active {

            color: #ffc107;

            font-weight: 600;

        }


        .navbar .btn-warning {

            font-weight: 600;

        }


        /* ================================
           PAGE HEADER
        ================================= */

        .menu-header {

            background:
                linear-gradient(
                    rgba(0, 0, 0, 0.75),
                    rgba(0, 0, 0, 0.75)
                ),
                url("assets/images/menu-banner.jpg");

            background-size: cover;

            background-position: center;

            padding: 90px 0;

            color: white;

            text-align: center;

        }


        .menu-header h1 {

            font-size: 48px;

            font-weight: bold;

        }


        .menu-header p {

            color: #ddd;

            font-size: 18px;

        }


        /* ================================
           MENU CARD
        ================================= */

        .menu-card {

            background-color: white;

            border: none;

            border-radius: 15px;

            overflow: hidden;

            height: 100%;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.08);

            transition: all 0.3s ease;

        }


        .menu-card:hover {

            transform: translateY(-8px);

            box-shadow:
                0 12px 30px
                rgba(0, 0, 0, 0.15);

        }


        /* ================================
           FOOD IMAGE
        ================================= */

        .food-image {

            width: 100%;

            height: 200px;

            object-fit: cover;

            display: block;

        }


        /* ================================
           NO IMAGE
        ================================= */

        .no-image {

            width: 100%;

            height: 200px;

            background-color: #f1f3f5;

            display: flex;

            align-items: center;

            justify-content: center;

        }


        .no-image i {

            font-size: 60px;

            color: #ffc107;

        }


        /* ================================
           CATEGORY
        ================================= */

        .category-badge {

            display: inline-block;

            background-color: #ffc107;

            color: #212529;

            padding: 5px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;

            margin-bottom: 10px;

        }


        /* ================================
           FOOD NAME
        ================================= */

        .food-name {

            font-size: 20px;

            font-weight: bold;

            margin-bottom: 8px;

        }


        /* ================================
           DESCRIPTION
        ================================= */

        .food-description {

            color: #6c757d;

            font-size: 14px;

            line-height: 1.6;

            min-height: 45px;

        }


        /* ================================
           PRICE
        ================================= */

        .food-price {

            font-size: 19px;

            font-weight: bold;

            color: #dc9f00;

        }


        /* ================================
           ORDER BUTTON
        ================================= */

        .order-btn {

            border-radius: 8px;

            font-weight: 600;

        }


        .order-btn:hover {

            background-color: #212529;

            color: white;

            border-color: #212529;

        }


        /* ================================
           EMPTY MENU
        ================================= */

        .empty-menu {

            padding: 80px 20px;

            text-align: center;

        }


        .empty-menu i {

            font-size: 70px;

            color: #adb5bd;

        }


        /* ================================
           FOOTER
        ================================= */

        footer {

            margin-top: 70px;

        }


        .footer-link {

            color: #adb5bd;

            text-decoration: none;

        }


        .footer-link:hover {

            color: #ffc107;

        }


        /* ================================
           MOBILE
        ================================= */

        @media (max-width: 768px) {

            .menu-header {

                padding: 60px 0;

            }


            .menu-header h1 {

                font-size: 36px;

            }


            .navbar .nav-item {

                margin-bottom: 5px;

            }


            .navbar .btn {

                margin-top: 5px;

            }

        }

    </style>

</head>


<body>


<!-- ==================================================
     NAVBAR
=================================================== -->

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
     PAGE HEADER
=================================================== -->

<section class="menu-header">


    <div class="container">


        <span class="badge bg-warning text-dark px-3 py-2 mb-3">

            BITECRAFT MENU

        </span>


        <h1>

            Our Menu

        </h1>


        <p class="mb-0">

            Delicious food made fresh for you.

        </p>


    </div>


</section>



<!-- ==================================================
     MENU SECTION
=================================================== -->

<section class="py-5">


    <div class="container">


        <!-- TITLE -->

        <div class="text-center mb-5">


            <span class="text-warning fw-bold">

                OUR FOOD

            </span>


            <h2 class="fw-bold mt-2">

                Explore Our Menu

            </h2>


            <p class="text-secondary">

                Choose your favorite food from our delicious selection.

            </p>


        </div>



        <!-- ==================================================
             CHECK MENU ITEMS
        =================================================== -->

        <?php if ($result && $result->num_rows > 0): ?>


            <div class="row g-4">


                <?php while ($item = $result->fetch_assoc()): ?>


                    <!-- MENU ITEM -->

                    <div
                        class="col-sm-6 col-lg-4 col-xl-3">


                        <div class="menu-card">


                            <!-- ================================
                                 IMAGE
                            ================================= -->


                            <?php if (!empty($item["image"])): ?>


                                <img
                                    src="assets/images/<?php echo htmlspecialchars($item["image"]); ?>"
                                    class="food-image"
                                    alt="<?php echo htmlspecialchars($item["name"]); ?>">


                            <?php else: ?>


                                <div class="no-image">


                                    <i
                                        class="bi bi-egg-fried">
                                    </i>


                                </div>


                            <?php endif; ?>



                            <!-- ================================
                                 CONTENT
                            ================================= -->


                            <div class="p-4">


                                <!-- CATEGORY -->


                                <?php if (!empty($item["category_name"])): ?>


                                    <span
                                        class="category-badge">


                                        <i
                                            class="bi bi-tag">
                                        </i>


                                        <?php

                                        echo htmlspecialchars(
                                            $item["category_name"]
                                        );

                                        ?>


                                    </span>


                                <?php endif; ?>



                                <!-- NAME -->


                                <h3
                                    class="food-name">


                                    <?php

                                    echo htmlspecialchars(
                                        $item["name"]
                                    );

                                    ?>


                                </h3>



                                <!-- DESCRIPTION -->


                                <p
                                    class="food-description">


                                    <?php

                                    if (
                                        !empty(
                                            $item["description"]
                                        )
                                    ) {

                                        echo htmlspecialchars(
                                            $item["description"]
                                        );

                                    } else {

                                        echo "Delicious food prepared with fresh ingredients.";

                                    }

                                    ?>


                                </p>



                                <!-- PRICE + ORDER -->


                                <div
                                    class="d-flex justify-content-between align-items-center mt-3">


                                    <!-- PRICE -->


                                    <span
                                        class="food-price">


                                        Rs.
                                        <?php

                                        echo number_format(
                                            $item["price"],
                                            2
                                        );

                                        ?>


                                    </span>



                                    <!-- ORDER -->

                                    <a
                                        href="add-to-cart.php?id=<?php echo $item["id"]; ?>"
                                        class="btn btn-warning order-btn">


                                        <i class="bi bi-cart-plus"></i>

                                        Add to Cart


                                    </a>


                                </div>


                            </div>


                        </div>


                    </div>


                <?php endwhile; ?>


            </div>


        <?php else: ?>


            <!-- ==================================================
                 NO MENU ITEMS
            =================================================== -->

            <div
                class="empty-menu">


                <i
                    class="bi bi-egg-fried">
                </i>


                <h3 class="mt-4">

                    No Menu Items Available

                </h3>


                <p class="text-secondary">

                    Our delicious menu will be available soon.

                </p>


                <a
                    href="index.php"
                    class="btn btn-warning">


                    <i
                        class="bi bi-house">
                    </i>


                    Back to Home


                </a>


            </div>


        <?php endif; ?>


    </div>


</section>



<!-- ==================================================
     FOOTER
=================================================== -->


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





<!-- ==================================================
     BOOTSTRAP JS
=================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>