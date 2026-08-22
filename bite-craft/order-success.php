<?php

session_start();


// Get last order ID

$order_id =
    $_SESSION["last_order_id"] ?? null;


if (!$order_id) {

    header("Location: index.php");

    exit;

}


// Remove from session

unset(
    $_SESSION["last_order_id"]
);

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Order Successful | BiteCraft</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>

        body {

            background: #f8f9fa;

        }


        .success-box {

            max-width: 600px;

            margin: 100px auto;

        }


        .success-icon {

            font-size: 80px;

            color: #198754;

        }

    </style>

</head>


<body>


<div class="container">


    <div
        class="card border-0 shadow-sm success-box">


        <div
            class="card-body text-center p-5">


            <i
                class="bi bi-check-circle-fill success-icon">
            </i>


            <h1 class="fw-bold mt-4">

                Order Placed Successfully!

            </h1>


            <p class="text-secondary">

                Thank you for ordering from BiteCraft.

            </p>


            <div
                class="alert alert-light border mt-4">


                <strong>

                    Order ID:

                </strong>


                #<?php echo (int) $order_id; ?>


            </div>


            <p class="text-secondary">

                Your order is currently

                <strong>

                    Pending

                </strong>.

                We will prepare your order soon.

            </p>


            <div class="d-flex gap-2 justify-content-center mt-4">


                <a
                    href="menu.php"
                    class="btn btn-warning">

                    <i class="bi bi-shop"></i>

                    Continue Shopping

                </a>


                <a
                    href="index.php"
                    class="btn btn-outline-dark">

                    <i class="bi bi-house"></i>

                    Home

                </a>


            </div>


        </div>


    </div>


</div>


</body>

</html>