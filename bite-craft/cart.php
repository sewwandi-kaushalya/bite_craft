<?php

session_start();


// ========================================
// CART
// ========================================

$cart = $_SESSION["cart"] ?? [];


// ========================================
// TOTAL
// ========================================

$total = 0;

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Cart | BiteCraft</title>


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

            background: #f8f9fa;

        }


        .cart-image {

            width: 80px;

            height: 70px;

            object-fit: cover;

            border-radius: 10px;

        }


        .cart-card {

            border: none;

            border-radius: 15px;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.07);

        }


        .price {

            font-weight: bold;

            color: #dc9f00;

        }


    </style>

</head>


<body>


<!-- ========================================
     NAVBAR
======================================== -->

<nav class="navbar navbar-dark bg-dark">

    <div class="container">


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


        <a
            href="menu.php"
            class="btn btn-outline-light btn-sm">

            <i class="bi bi-arrow-left"></i>

            Continue Shopping

        </a>


    </div>

</nav>



<!-- ========================================
     CONTENT
======================================== -->

<div class="container py-5">


    <h2 class="fw-bold mb-4">

        <i class="bi bi-cart3 text-warning"></i>

        Your Cart

    </h2>



    <?php if (!empty($cart)): ?>


        <div class="row g-4">


            <!-- ==================================
                 CART ITEMS
            =================================== -->

            <div class="col-lg-8">


                <div class="card cart-card">


                    <div class="card-body">


                        <?php foreach ($cart as $item): ?>


                            <?php

                            $subtotal =
                                $item["price"]
                                *
                                $item["quantity"];

                            $total += $subtotal;

                            ?>


                            <div
                                class="d-flex align-items-center border-bottom py-3">


                                <!-- IMAGE -->


                                <?php if (!empty($item["image"])): ?>


                                    <img
                                        src="assets/images/<?php echo htmlspecialchars($item["image"]); ?>"
                                        class="cart-image me-3"
                                        alt="<?php echo htmlspecialchars($item["name"]); ?>">


                                <?php else: ?>


                                    <div
                                        class="cart-image bg-light d-flex align-items-center justify-content-center me-3">

                                        <i
                                            class="bi bi-egg-fried text-warning fs-3">
                                        </i>

                                    </div>


                                <?php endif; ?>



                                <!-- INFO -->


                                <div class="flex-grow-1">


                                    <h5 class="mb-1">

                                        <?php

                                        echo htmlspecialchars(
                                            $item["name"]
                                        );

                                        ?>

                                    </h5>


                                    <small class="text-secondary">

                                        Rs.
                                        <?php

                                        echo number_format(
                                            $item["price"],
                                            2
                                        );

                                        ?>

                                        each

                                    </small>


                                </div>



                                <!-- QUANTITY -->


                                <div class="me-4">


                                    <span class="badge bg-dark">

                                        Qty:
                                        <?php

                                        echo $item["quantity"];

                                        ?>

                                    </span>


                                </div>



                                <!-- SUBTOTAL -->


                                <div
                                    class="text-end me-3">


                                    <strong
                                        class="price">


                                        Rs.
                                        <?php

                                        echo number_format(
                                            $subtotal,
                                            2
                                        );

                                        ?>


                                    </strong>


                                </div>



                                <!-- REMOVE -->


                                <a
                                    href="remove-from-cart.php?id=<?php echo $item["id"]; ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Remove this item from cart?');">


                                    <i
                                        class="bi bi-trash">
                                    </i>


                                </a>


                            </div>


                        <?php endforeach; ?>


                    </div>


                </div>


            </div>



            <!-- ==================================
                 SUMMARY
            =================================== -->

            <div class="col-lg-4">


                <div class="card cart-card">


                    <div class="card-body p-4">


                        <h4 class="fw-bold mb-4">

                            Order Summary

                        </h4>


                        <div
                            class="d-flex justify-content-between mb-3">


                            <span>

                                Subtotal

                            </span>


                            <strong>

                                Rs.
                                <?php

                                echo number_format(
                                    $total,
                                    2
                                );

                                ?>

                            </strong>


                        </div>


                        <div
                            class="d-flex justify-content-between mb-3">


                            <span>

                                Delivery

                            </span>


                            <strong>

                                Free

                            </strong>


                        </div>


                        <hr>


                        <div
                            class="d-flex justify-content-between mb-4">


                            <h5 class="fw-bold">

                                Total

                            </h5>


                            <h5
                                class="fw-bold text-warning">


                                Rs.
                                <?php

                                echo number_format(
                                    $total,
                                    2
                                );

                                ?>


                            </h5>


                        </div>


                        <a
                            href="checkout.php"
                            class="btn btn-warning w-100 py-2">


                            <i
                                class="bi bi-credit-card">
                            </i>


                            Proceed to Checkout


                        </a>


                    </div>


                </div>


            </div>


        </div>


    <?php else: ?>


        <!-- ==================================
             EMPTY CART
        =================================== -->


        <div
            class="text-center py-5">


            <i
                class="bi bi-cart-x text-secondary"
                style="font-size: 80px;">
            </i>


            <h3 class="mt-4">

                Your Cart is Empty

            </h3>


            <p class="text-secondary">

                You haven't added anything to your cart yet.

            </p>


            <a
                href="menu.php"
                class="btn btn-warning">


                <i class="bi bi-shop"></i>

                Browse Menu


            </a>


        </div>


    <?php endif; ?>


</div>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>