
<?php

session_start();

require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";


    // ========================================
    // CHECK EMPTY FIELDS
    // ========================================

    if (
        empty($name) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {

        $error = "Please fill all fields.";

    }


    // ========================================
    // CHECK PASSWORD MATCH
    // ========================================

    elseif ($password !== $confirm_password) {

        $error = "Passwords do not match.";

    }


    // ========================================
    // CHECK PASSWORD LENGTH
    // ========================================

    elseif (strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    }


    // ========================================
    // CHECK EMAIL
    // ========================================

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    }


    else {

        // ========================================
        // CHECK EXISTING EMAIL
        // ========================================

        $stmt = $conn->prepare(
            "SELECT id
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();


        if ($result->num_rows > 0) {

            $error = "This email is already registered.";

        }

        else {

            // ========================================
            // HASH PASSWORD
            // ========================================

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            // ========================================
            // INSERT USER
            // ========================================

            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, email, password, role)
                VALUES (?, ?, ?, 'customer')"
            );

            $stmt->bind_param(
                "sss",
                $name,
                $email,
                $hashed_password
            );


            // ========================================
            // REGISTRATION SUCCESS
            // ========================================

            if ($stmt->execute()) {

                // Get newly created user ID
                $new_user_id = $stmt->insert_id;


                // ========================================
                // AUTOMATIC LOGIN
                // ========================================

                $_SESSION["user_id"] = $new_user_id;

                $_SESSION["user_name"] = $name;

                $_SESSION["user_role"] = "customer";


                // ========================================
                // REDIRECT TO HOME
                // ========================================

                header("Location: index.php");

                exit;

            }

            else {

                $error =
                    "Something went wrong. Please try again.";

            }


            $stmt->close();

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

    <title>Register | BiteCraft</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>


<body class="bg-light">


<div class="container">


    <div
        class="row justify-content-center align-items-center"
        style="min-height: 100vh;">


        <div class="col-md-6 col-lg-5">


            <div class="card border-0 shadow">


                <div class="card-body p-5">


                    <!-- ========================================
                         LOGO
                    ======================================== -->

                    <div class="text-center mb-4">


                        <i
                            class="bi bi-egg-fried text-warning"
                            style="font-size: 50px;">
                        </i>


                        <h2 class="fw-bold">

                            Create Account

                        </h2>


                        <p class="text-secondary">

                            Join BiteCraft today

                        </p>


                    </div>



                    <!-- ========================================
                         ERROR MESSAGE
                    ======================================== -->

                    <?php if ($error): ?>

                        <div class="alert alert-danger">

                            <i class="bi bi-exclamation-circle"></i>

                            <?php

                            echo htmlspecialchars($error);

                            ?>

                        </div>

                    <?php endif; ?>



                    <!-- ========================================
                         REGISTER FORM
                    ======================================== -->

                    <form method="POST">


                        <!-- NAME -->

                        <div class="mb-3">

                            <label class="form-label">

                                Full Name

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-person"></i>

                                </span>


                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="Enter your name"
                                    value="<?php echo htmlspecialchars($_POST["name"] ?? ""); ?>"
                                    required>

                            </div>

                        </div>



                        <!-- EMAIL -->

                        <div class="mb-3">

                            <label class="form-label">

                                Email Address

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-envelope"></i>

                                </span>


                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Enter your email"
                                    value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>"
                                    required>

                            </div>

                        </div>



                        <!-- PASSWORD -->

                        <div class="mb-3">

                            <label class="form-label">

                                Password

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-lock"></i>

                                </span>


                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Create a password"
                                    required>

                            </div>

                        </div>



                        <!-- CONFIRM PASSWORD -->

                        <div class="mb-4">

                            <label class="form-label">

                                Confirm Password

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-lock-fill"></i>

                                </span>


                                <input
                                    type="password"
                                    name="confirm_password"
                                    class="form-control"
                                    placeholder="Confirm your password"
                                    required>

                            </div>

                        </div>



                        <!-- SUBMIT -->

                        <button
                            type="submit"
                            class="btn btn-warning w-100 py-2">

                            <i class="bi bi-person-plus"></i>

                            Create Account

                        </button>


                    </form>



                    <!-- ========================================
                         LOGIN LINK
                    ======================================== -->

                    <div class="text-center mt-4">

                        <span class="text-secondary">

                            Already have an account?

                        </span>


                        <a
                            href="login.php"
                            class="text-warning fw-bold text-decoration-none">

                            Login

                        </a>

                    </div>



                    <!-- ========================================
                         HOME LINK
                    ======================================== -->

                    <div class="text-center mt-3">

                        <a
                            href="index.php"
                            class="text-decoration-none">

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

