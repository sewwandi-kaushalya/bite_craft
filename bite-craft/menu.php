<?php

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

        .navbar-brand {

            font-size: 25px;

        }


        .navbar-brand span {

            color: #ffc107;

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

            height: 230px;

            object-fit: cover;

        }


        /* ================================
           NO IMAGE
        ================================= */

        .no-image {

            width: 100%;

            height: 230px;

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

            font-size: 20px;

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

        }

    </style>

</head>


<body>


<!-- ==================================================
     NAVBAR
=================================================== -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">


    <div class="container">


        <!-- LOGO -->

        <a
            class="navbar-brand fw-bold"
            href="index.php">

            <i
                class="bi bi-egg-fried text-warning">
            </i>

            Bite<span>Craft</span>

        </a>



        <!-- MOBILE BUTTON -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>



        <!-- NAVIGATION -->

        <div
            class="collapse navbar-collapse"
            id="mainNavbar">


            <ul
                class="navbar-nav ms-auto align-items-lg-center">


                <!-- HOME -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php">

                        Home

                    </a>

                </li>



                <!-- MENU -->

                <li class="nav-item">

                    <a
                        class="nav-link active"
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



                <!-- LOGIN -->

                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">

                    <a
                        href="login.php"
                        class="btn btn-warning">

                        <i
                            class="bi bi-person">
                        </i>

                        Login

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


            <h2 class="fw-bold">

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

<footer
    class="bg-dark text-white py-5">


    <div class="container">


        <div class="row g-4">


            <!-- ABOUT -->

            <div class="col-md-4">


                <h5 class="fw-bold">


                    <i
                        class="bi bi-egg-fried text-warning">
                    </i>


                    Bite<span class="text-warning">

                        Craft

                    </span>


                </h5>


                <p class="text-secondary">


                    Delicious food, quality ingredients,
                    and unforgettable experiences.


                </p>


            </div>



            <!-- QUICK LINKS -->

            <div class="col-md-4">


                <h5 class="fw-bold">

                    Quick Links

                </h5>


                <ul
                    class="list-unstyled">


                    <li class="mb-2">

                        <a
                            href="index.php"
                            class="text-secondary text-decoration-none">

                            Home

                        </a>

                    </li>


                    <li class="mb-2">

                        <a
                            href="menu.php"
                            class="text-secondary text-decoration-none">

                            Menu

                        </a>

                    </li>


                    <li class="mb-2">

                        <a
                            href="about.php"
                            class="text-secondary text-decoration-none">

                            About

                        </a>

                    </li>


                    <li>

                        <a
                            href="contact.php"
                            class="text-secondary text-decoration-none">

                            Contact

                        </a>

                    </li>


                </ul>


            </div>



            <!-- CONTACT -->

            <div class="col-md-4">


                <h5 class="fw-bold">

                    Contact Us

                </h5>


                <p class="text-secondary mb-2">


                    <i
                        class="bi bi-geo-alt text-warning">
                    </i>


                    Colombo, Sri Lanka


                </p>


                <p class="text-secondary mb-2">


                    <i
                        class="bi bi-telephone text-warning">
                    </i>


                    +94 77 123 4567


                </p>


                <p class="text-secondary">


                    <i
                        class="bi bi-envelope text-warning">
                    </i>


                    info@bitecraft.com


                </p>


            </div>


        </div>



        <hr class="border-secondary mt-4">


        <div class="text-center">


            <p
                class="text-secondary mb-0">


                © <?php echo date("Y"); ?>

                BiteCraft.

                All Rights Reserved.


            </p>


        </div>


    </div>


</footer>



<!-- ==================================================
     BOOTSTRAP JS
=================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>