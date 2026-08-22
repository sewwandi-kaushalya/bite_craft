<?php

session_start();

require_once "config/database.php";

$error = "";

// Login form submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";


    // Validate fields
    if (empty($email) || empty($password)) {

        $error = "Please enter your email and password.";

    } else {

        // Find user by email
        $stmt = $conn->prepare(
            "SELECT id, name, email, password, role
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();


        // User found
        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();


            // Verify password
            if (password_verify($password, $user["password"])) {


                // Create session
                $_SESSION["user_id"] = $user["id"];

                $_SESSION["user_name"] = $user["name"];

                $_SESSION["user_email"] = $user["email"];

                $_SESSION["user_role"] = $user["role"];


                // =========================
                // ADMIN
                // =========================

                if ($user["role"] === "admin") {

                    header("Location: admin/index.php");

                    exit;
                }


                // =========================
                // CUSTOMER
                // =========================

                if ($user["role"] === "customer") {

                    header("Location: index.php");

                    exit;
                }


                // Unknown role

                $error = "Invalid user role.";

            } else {

                $error = "Invalid email or password.";

            }

        } else {

            $error = "Invalid email or password.";

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

    <title>Login | BiteCraft</title>


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

</head>


<body class="bg-light">


<!-- =========================================
     LOGIN PAGE
========================================= -->

<div class="container">


    <div
        class="row justify-content-center align-items-center"
        style="min-height: 100vh;">


        <div class="col-md-6 col-lg-5">


            <div class="card border-0 shadow">


                <div class="card-body p-5">


                    <!-- =================================
                         LOGO
                    ================================= -->

                    <div class="text-center mb-4">


                        <a
                            href="index.php"
                            class="text-decoration-none text-dark">


                            <i
                                class="bi bi-egg-fried text-warning"
                                style="font-size: 50px;">
                            </i>


                            <h2 class="fw-bold mb-1">

                                Bite<span class="text-warning">

                                    Craft

                                </span>

                            </h2>


                        </a>


                        <p class="text-secondary">

                            Welcome back! Please login to your account.

                        </p>


                    </div>



                    <!-- =================================
                         ERROR MESSAGE
                    ================================= -->

                    <?php if (!empty($error)): ?>

                        <div
                            class="alert alert-danger d-flex align-items-center">

                            <i
                                class="bi bi-exclamation-circle-fill me-2">
                            </i>


                            <span>

                                <?php echo htmlspecialchars($error); ?>

                            </span>

                        </div>

                    <?php endif; ?>



                    <!-- =================================
                         LOGIN FORM
                    ================================= -->

                    <form
                        method="POST"
                        action="">


                        <!-- EMAIL -->

                        <div class="mb-3">


                            <label
                                for="email"
                                class="form-label fw-semibold">

                                Email Address

                            </label>


                            <div class="input-group">


                                <span class="input-group-text">

                                    <i class="bi bi-envelope"></i>

                                </span>


                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Enter your email"
                                    value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>"
                                    required>

                            </div>


                        </div>



                        <!-- PASSWORD -->

                        <div class="mb-4">


                            <label
                                for="password"
                                class="form-label fw-semibold">

                                Password

                            </label>


                            <div class="input-group">


                                <span class="input-group-text">

                                    <i class="bi bi-lock"></i>

                                </span>


                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Enter your password"
                                    required>

                            </div>


                        </div>



                        <!-- LOGIN BUTTON -->

                        <button
                            type="submit"
                            class="btn btn-warning w-100 py-2 fw-semibold">


                            <i class="bi bi-box-arrow-in-right me-1"></i>

                            Login


                        </button>


                    </form>



                    <!-- =================================
                         REGISTER
                    ================================= -->

                    <div class="text-center mt-4">


                        <span class="text-secondary">

                            Don't have an account?

                        </span>


                        <a
                            href="register.php"
                            class="text-warning fw-bold text-decoration-none">

                            Register

                        </a>


                    </div>



                    <!-- =================================
                         BACK TO HOME
                    ================================= -->

                    <div class="text-center mt-3">


                        <a
                            href="index.php"
                            class="text-secondary text-decoration-none">

                            <i class="bi bi-arrow-left"></i>

                            Back to Home

                        </a>


                    </div>


                </div>


            </div>


        </div>


    </div>


</div>


</body>

</html>