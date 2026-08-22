<?php

session_start();

require_once "../config/database.php";

// Admin check
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {

    header("Location: ../login.php");
    exit;
}


// Get menu items
$sql = "SELECT menu_items.*, categories.name AS category_name
        FROM menu_items
        LEFT JOIN categories
        ON menu_items.category_id = categories.id
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

    <title>Menu Management | BiteCraft</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>


<body class="bg-light">


<!-- NAVBAR -->

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

                <?php echo htmlspecialchars($_SESSION["user_name"]); ?>

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



<!-- CONTENT -->

<div class="container py-5">


    <div class="d-flex justify-content-between align-items-center mb-4">


        <div>

            <h2 class="fw-bold mb-1">

                Menu Management

            </h2>

            <p class="text-secondary mb-0">

                Manage your restaurant menu items.

            </p>

        </div>


        <a
            href="add-menu.php"
            class="btn btn-warning">

            <i class="bi bi-plus-circle"></i>

            Add Menu Item

        </a>


    </div>



    <!-- MENU TABLE -->

    <div class="card border-0 shadow-sm">


        <div class="card-body">


            <div class="table-responsive">


                <table class="table align-middle">


                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Image</th>

                            <th>Name</th>

                            <th>Category</th>

                            <th>Price</th>

                            <th>Status</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php if ($result && $result->num_rows > 0): ?>


                        <?php while ($item = $result->fetch_assoc()): ?>


                            <tr>


                                <!-- ID -->

                                <td>

                                    <?php echo $item["id"]; ?>

                                </td>



                                <!-- IMAGE -->

                                <td>

                                    <?php if (!empty($item["image"])): ?>

                                        <img
                                            src="../assets/images/<?php echo htmlspecialchars($item["image"]); ?>"
                                            alt="Food"
                                            width="70"
                                            height="55"
                                            style="object-fit: cover;"
                                            class="rounded">

                                    <?php else: ?>

                                        <div
                                            class="bg-light border rounded d-flex align-items-center justify-content-center"
                                            style="width:70px;height:55px;">

                                            <i
                                                class="bi bi-egg-fried text-warning fs-4">
                                            </i>

                                        </div>

                                    <?php endif; ?>

                                </td>



                                <!-- NAME -->

                                <td>

                                    <strong>

                                        <?php echo htmlspecialchars($item["name"]); ?>

                                    </strong>


                                    <?php if (!empty($item["description"])): ?>

                                        <br>

                                        <small class="text-secondary">

                                            <?php

                                            echo htmlspecialchars(
                                                substr($item["description"], 0, 50)
                                            );

                                            ?>

                                        </small>

                                    <?php endif; ?>

                                </td>



                                <!-- CATEGORY -->

                                <td>

                                    <span class="badge bg-secondary">

                                        <?php

                                        echo htmlspecialchars(
                                            $item["category_name"] ?? "No Category"
                                        );

                                        ?>

                                    </span>

                                </td>



                                <!-- PRICE -->

                                <td>

                                    <strong>

                                        Rs.
                                        <?php

                                        echo number_format(
                                            $item["price"],
                                            2
                                        );

                                        ?>

                                    </strong>

                                </td>



                                <!-- STATUS -->

                                <td>


                                    <?php if ($item["status"] === "available"): ?>

                                        <span class="badge bg-success">

                                            Available

                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger">

                                            Unavailable

                                        </span>

                                    <?php endif; ?>


                                </td>



                                <!-- ACTIONS -->

                                <td>


                                    <a
                                        href="edit-menu.php?id=<?php echo $item["id"]; ?>"
                                        class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    <a
                                        href="delete-menu.php?id=<?php echo $item["id"]; ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this menu item?');">

                                        <i class="bi bi-trash"></i>

                                    </a>


                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5">

                                <i
                                    class="bi bi-egg-fried text-secondary fs-1">
                                </i>


                                <h5 class="mt-3">

                                    No Menu Items Found

                                </h5>


                                <p class="text-secondary">

                                    Add your first menu item.

                                </p>


                                <a
                                    href="add-menu.php"
                                    class="btn btn-warning">

                                    <i class="bi bi-plus-circle"></i>

                                    Add Menu Item

                                </a>

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>


                </table>


            </div>


        </div>


    </div>


</div>


</body>

</html>