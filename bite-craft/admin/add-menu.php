<?php

session_start();

require_once "../config/database.php";


// ========================================
// ADMIN CHECK
// ========================================

if (
    !isset($_SESSION["user_id"]) ||
    $_SESSION["user_role"] !== "admin"
) {

    header("Location: ../login.php");

    exit;
}


$error = "";
$success = "";


// ========================================
// ADD MENU ITEM
// ========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $name = trim($_POST["name"] ?? "");

    $description = trim($_POST["description"] ?? "");

    $price = trim($_POST["price"] ?? "");

    $category_id = $_POST["category_id"] ?? "";

    $status = $_POST["status"] ?? "available";


    // ====================================
    // VALIDATION
    // ====================================

    if (
        empty($name) ||
        empty($price) ||
        empty($category_id)
    ) {

        $error = "Please fill all required fields.";

    }


    elseif (!is_numeric($price) || $price <= 0) {

        $error = "Please enter a valid price.";

    }


    else {


        // =================================
        // IMAGE UPLOAD
        // =================================

        $imageName = "";


        if (
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] === 0
        ) {


            $image = $_FILES["image"];


            $allowedTypes = [
                "image/jpeg",
                "image/png",
                "image/webp"
            ];


            if (!in_array($image["type"], $allowedTypes)) {

                $error = "Only JPG, PNG and WEBP images are allowed.";

            }


            elseif ($image["size"] > 5 * 1024 * 1024) {

                $error = "Image size must be less than 5MB.";

            }


            else {


                // Create unique filename

                $extension = pathinfo(
                    $image["name"],
                    PATHINFO_EXTENSION
                );


                $imageName =
                    uniqid("food_", true)
                    . "."
                    . strtolower($extension);


                $uploadDirectory =
                    "../assets/images/";


                // Create folder if not exists

                if (!is_dir($uploadDirectory)) {

                    mkdir(
                        $uploadDirectory,
                        0777,
                        true
                    );

                }


                $uploadPath =
                    $uploadDirectory
                    . $imageName;


                if (
                    !move_uploaded_file(
                        $image["tmp_name"],
                        $uploadPath
                    )
                ) {

                    $error = "Failed to upload image.";

                }

            }

        }


        // =================================
        // INSERT DATABASE
        // =================================

        if (empty($error)) {


            $stmt = $conn->prepare(
                "INSERT INTO menu_items
                (
                    name,
                    description,
                    price,
                    category_id,
                    image,
                    status
                )
                VALUES (?, ?, ?, ?, ?, ?)"
            );


            $stmt->bind_param(
                "ssdiss",
                $name,
                $description,
                $price,
                $category_id,
                $imageName,
                $status
            );


            if ($stmt->execute()) {

                $success =
                    "Menu item added successfully!";


                // Clear form values

                $name = "";

                $description = "";

                $price = "";

                $category_id = "";

                $status = "available";

            }
            else {

                $error =
                    "Database error: "
                    . $stmt->error;

            }

        }

    }

}


// ========================================
// GET CATEGORIES
// ========================================

$categories = $conn->query(
    "SELECT id, name
     FROM categories
     ORDER BY name ASC"
);

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Add Menu Item | BiteCraft</title>


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


<!-- ========================================
     NAVBAR
======================================== -->

<nav class="navbar navbar-dark bg-dark">

    <div class="container-fluid">


        <a
            href="index.php"
            class="navbar-brand fw-bold">

            <i class="bi bi-egg-fried text-warning"></i>

            Bite<span class="text-warning">Craft</span>

        </a>


        <div>


            <span class="text-white me-3">

                <i class="bi bi-person-circle"></i>

                <?php

                echo htmlspecialchars(
                    $_SESSION["user_name"]
                );

                ?>

            </span>


            <a
                href="logout.php"
                class="btn btn-outline-light btn-sm">

                <i class="bi bi-box-arrow-right"></i>

                Logout

            </a>


        </div>


    </div>

</nav>



<!-- ========================================
     MAIN CONTENT
======================================== -->

<div class="container py-5">


    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">


        <div>


            <h2 class="fw-bold">

                Add Menu Item

            </h2>


            <p class="text-secondary mb-0">

                Add a new food item to your restaurant menu.

            </p>


        </div>


<ul>
       <li class="list-unstyled"> <a
            href="menu.php"
            class="btn btn-outline-dark">

            <i class="bi bi-arrow-left"></i>

            Back to Menu

        </a> </li>

        <li class="list-unstyled mt-2">
            <!-- Dashboard -->

    <a
        href="index.php"
        class="btn btn-outline-dark">

        <i class="bi bi-speedometer2"></i>

        <span>Dashboard</span>

    </a></li>
</ul>

    </div>



    <!-- CARD -->

    <div class="card border-0 shadow-sm">


        <div class="card-body p-4 p-md-5">


            <!-- SUCCESS -->

            <?php if (!empty($success)): ?>

                <div
                    class="alert alert-success">

                    <i
                        class="bi bi-check-circle-fill me-2">
                    </i>

                    <?php echo htmlspecialchars($success); ?>


                    <a
                        href="menu.php"
                        class="alert-link ms-2">

                        View Menu

                    </a>

                </div>

            <?php endif; ?>



            <!-- ERROR -->

            <?php if (!empty($error)): ?>

                <div
                    class="alert alert-danger">

                    <i
                        class="bi bi-exclamation-circle-fill me-2">
                    </i>

                    <?php echo htmlspecialchars($error); ?>

                </div>

            <?php endif; ?>



            <!-- FORM -->

            <form
                method="POST"
                enctype="multipart/form-data">


                <div class="row g-4">


                    <!-- FOOD NAME -->

                    <div class="col-md-8">


                        <label
                            class="form-label fw-semibold">

                            Food Name
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Example: Chicken Burger"
                            value="<?php echo htmlspecialchars($name ?? ''); ?>"
                            required>


                    </div>



                    <!-- PRICE -->

                    <div class="col-md-4">


                        <label
                            class="form-label fw-semibold">

                            Price
                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">


                            <span class="input-group-text">

                                Rs.

                            </span>


                            <input
                                type="number"
                                name="price"
                                class="form-control"
                                placeholder="2500"
                                step="0.01"
                                min="0"
                                value="<?php echo htmlspecialchars($price ?? ''); ?>"
                                required>


                        </div>


                    </div>



                    <!-- CATEGORY -->

                    <div class="col-md-6">


                        <label
                            class="form-label fw-semibold">

                            Category
                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="category_id"
                            class="form-select"
                            required>


                            <option value="">

                                Select Category

                            </option>


                            <?php if ($categories && $categories->num_rows > 0): ?>


                                <?php while ($category = $categories->fetch_assoc()): ?>


                                    <option
                                        value="<?php echo $category["id"]; ?>"
                                        <?php

                                        if (
                                            isset($category_id) &&
                                            $category_id == $category["id"]
                                        ) {

                                            echo "selected";

                                        }

                                        ?>>

                                        <?php

                                        echo htmlspecialchars(
                                            $category["name"]
                                        );

                                        ?>

                                    </option>


                                <?php endwhile; ?>


                            <?php endif; ?>


                        </select>


                    </div>



                    <!-- STATUS -->

                    <div class="col-md-6">


                        <label
                            class="form-label fw-semibold">

                            Status

                        </label>


                        <select
                            name="status"
                            class="form-select">


                            <option
                                value="available"
                                <?php

                                if (
                                    ($status ?? "available")
                                    === "available"
                                ) {

                                    echo "selected";

                                }

                                ?>>

                                Available

                            </option>


                            <option
                                value="unavailable"
                                <?php

                                if (
                                    ($status ?? "")
                                    === "unavailable"
                                ) {

                                    echo "selected";

                                }

                                ?>>

                                Unavailable

                            </option>


                        </select>


                    </div>



                    <!-- DESCRIPTION -->

                    <div class="col-12">


                        <label
                            class="form-label fw-semibold">

                            Description

                        </label>


                        <textarea
                            name="description"
                            class="form-control"
                            rows="4"
                            placeholder="Describe the food item..."><?php

                            echo htmlspecialchars(
                                $description ?? ''
                            );

                            ?></textarea>


                    </div>



                    <!-- IMAGE -->

                    <div class="col-12">


                        <label
                            class="form-label fw-semibold">

                            Food Image

                        </label>


                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp">


                        <small class="text-secondary">

                            JPG, PNG or WEBP. Maximum 5MB.

                        </small>


                    </div>



                    <!-- BUTTONS -->

                    <div class="col-12">


                        <hr>


                        <div
                            class="d-flex gap-2 justify-content-end">


                            <a
                                href="menu.php"
                                class="btn btn-secondary">

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="btn btn-warning">

                                <i
                                    class="bi bi-plus-circle">
                                </i>

                                Add Menu Item

                            </button>


                        </div>


                    </div>


                </div>


            </form>


        </div>


    </div>


</div>


</body>

</html>