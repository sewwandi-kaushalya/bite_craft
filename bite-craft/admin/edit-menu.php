<?php

session_start();

require_once "../config/database.php";


// Admin check
if (
    !isset($_SESSION["user_id"]) ||
    $_SESSION["user_role"] !== "admin"
) {
    header("Location: ../login.php");
    exit;
}


$error = "";
$success = "";


// Get ID
$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($id <= 0) {
    header("Location: menu.php");
    exit;
}


// Get menu item
$stmt = $conn->prepare(
    "SELECT * FROM menu_items WHERE id = ? LIMIT 1"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: menu.php");
    exit;
}

$item = $result->fetch_assoc();


// Current values
$name = $item["name"];
$description = $item["description"];
$price = $item["price"];
$category_id = $item["category_id"];
$status = $item["status"];
$currentImage = $item["image"];


// Update
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $category_id = $_POST["category_id"] ?? "";
    $status = $_POST["status"] ?? "available";


    // Validation
    if (
        empty($name) ||
        empty($price) ||
        empty($category_id)
    ) {

        $error = "Please fill all required fields.";

    } elseif (!is_numeric($price) || $price <= 0) {

        $error = "Please enter a valid price.";

    } else {

        $newImage = $currentImage;


        // Image upload
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

                $error =
                    "Only JPG, PNG and WEBP images are allowed.";

            } elseif ($image["size"] > 5 * 1024 * 1024) {

                $error =
                    "Image size must be less than 5MB.";

            } else {

                $extension = pathinfo(
                    $image["name"],
                    PATHINFO_EXTENSION
                );

                $newImage =
                    uniqid("food_", true)
                    . "."
                    . strtolower($extension);


                $uploadDirectory = "../assets/images/";

                if (!is_dir($uploadDirectory)) {

                    mkdir(
                        $uploadDirectory,
                        0777,
                        true
                    );

                }


                $uploadPath =
                    $uploadDirectory . $newImage;


                if (
                    move_uploaded_file(
                        $image["tmp_name"],
                        $uploadPath
                    )
                ) {

                    // Delete old image
                    if (
                        !empty($currentImage) &&
                        file_exists(
                            $uploadDirectory . $currentImage
                        )
                    ) {

                        unlink(
                            $uploadDirectory . $currentImage
                        );
                    }

                } else {

                    $error =
                        "Failed to upload new image.";

                }
            }
        }


        // Update database
        if (empty($error)) {

            $stmt = $conn->prepare(
                "UPDATE menu_items
                 SET
                    name = ?,
                    description = ?,
                    price = ?,
                    category_id = ?,
                    image = ?,
                    status = ?
                 WHERE id = ?"
            );


            $stmt->bind_param(
                "ssdissi",
                $name,
                $description,
                $price,
                $category_id,
                $newImage,
                $status,
                $id
            );


            if ($stmt->execute()) {

                $success =
                    "Menu item updated successfully!";

                $currentImage = $newImage;

            } else {

                $error =
                    "Database error: " . $stmt->error;
            }
        }
    }
}


// Categories
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

    <title>Edit Menu Item | BiteCraft</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>


<body class="bg-light">


<!-- Navbar -->

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



<!-- Content -->

<div class="container py-5">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">

                Edit Menu Item

            </h2>

            <p class="text-secondary mb-0">

                Update your food item information.

            </p>

        </div>


        <a
            href="menu.php"
            class="btn btn-outline-dark">

            <i class="bi bi-arrow-left"></i>

            Back to Menu

        </a>

    </div>



    <div class="card border-0 shadow-sm">

        <div class="card-body p-4 p-md-5">


            <?php if (!empty($success)): ?>

                <div class="alert alert-success">

                    <i class="bi bi-check-circle-fill me-2"></i>

                    <?php echo htmlspecialchars($success); ?>

                </div>

            <?php endif; ?>


            <?php if (!empty($error)): ?>

                <div class="alert alert-danger">

                    <i class="bi bi-exclamation-circle-fill me-2"></i>

                    <?php echo htmlspecialchars($error); ?>

                </div>

            <?php endif; ?>



            <form
                method="POST"
                enctype="multipart/form-data">


                <div class="row g-4">


                    <!-- Name -->

                    <div class="col-md-8">

                        <label class="form-label fw-semibold">

                            Food Name
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="<?php echo htmlspecialchars($name); ?>"
                            required>

                    </div>



                    <!-- Price -->

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

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
                                step="0.01"
                                min="0"
                                value="<?php echo htmlspecialchars($price); ?>"
                                required>

                        </div>

                    </div>



                    <!-- Category -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

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


                            <?php while ($category = $categories->fetch_assoc()): ?>

                                <option
                                    value="<?php echo $category["id"]; ?>"
                                    <?php

                                    if (
                                        $category_id ==
                                        $category["id"]
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

                        </select>

                    </div>



                    <!-- Status -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Status

                        </label>


                        <select
                            name="status"
                            class="form-select">


                            <option
                                value="available"
                                <?php

                                if (
                                    $status === "available"
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
                                    $status === "unavailable"
                                ) {
                                    echo "selected";
                                }

                                ?>>

                                Unavailable

                            </option>

                        </select>

                    </div>



                    <!-- Description -->

                    <div class="col-12">

                        <label class="form-label fw-semibold">

                            Description

                        </label>


                        <textarea
                            name="description"
                            class="form-control"
                            rows="4"><?php

                            echo htmlspecialchars(
                                $description
                            );

                            ?></textarea>

                    </div>



                    <!-- Current Image -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Current Image

                        </label>


                        <?php if (!empty($currentImage)): ?>

                            <br>

                            <img
                                src="../assets/images/<?php echo htmlspecialchars($currentImage); ?>"
                                width="180"
                                height="130"
                                style="object-fit:cover;"
                                class="rounded">

                        <?php else: ?>

                            <p class="text-secondary">

                                No image available.

                            </p>

                        <?php endif; ?>

                    </div>



                    <!-- New Image -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            New Image

                        </label>


                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp">


                        <small class="text-secondary">

                            Leave empty to keep the current image.

                        </small>

                    </div>



                    <!-- Buttons -->

                    <div class="col-12">

                        <hr>


                        <div class="d-flex justify-content-end gap-2">


                            <a
                                href="menu.php"
                                class="btn btn-secondary">

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="btn btn-warning">

                                <i class="bi bi-save"></i>

                                Update Menu Item

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