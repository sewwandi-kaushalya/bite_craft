
<?php

session_start();

require_once "../config/database.php";


// ========================================
// CHECK ADMIN LOGIN
// ========================================

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit;
}


// ========================================
// CHECK ADMIN ROLE
// ========================================

if (
    !isset($_SESSION["user_role"]) ||
    $_SESSION["user_role"] !== "admin"
) {

    header("Location: ../index.php");
    exit;
}


// ========================================
// ADMIN NAME
// ========================================

$admin_name = $_SESSION["user_name"] ?? "Admin";


// ========================================
// SEARCH
// ========================================

$search = trim($_GET["search"] ?? "");


// ========================================
// GET USERS
// ========================================

if ($search !== "") {

    $stmt = $conn->prepare(
        "SELECT
            id,
            name,
            email,
            role,
            created_at
         FROM users
         WHERE name LIKE ?
            OR email LIKE ?
            OR role LIKE ?
         ORDER BY id DESC"
    );

    $search_value = "%" . $search . "%";

    $stmt->bind_param(
        "sss",
        $search_value,
        $search_value,
        $search_value
    );

} else {

    $stmt = $conn->prepare(
        "SELECT
            id,
            name,
            email,
            role,
            created_at
         FROM users
         ORDER BY id DESC"
    );
}


$stmt->execute();

$users = $stmt->get_result();


// ========================================
// USER COUNTS
// ========================================

$total_users = 0;
$total_customers = 0;
$total_admins = 0;


$count_result = $conn->query(
    "SELECT
        COUNT(*) AS total_users,
        SUM(role = 'customer') AS total_customers,
        SUM(role = 'admin') AS total_admins
     FROM users"
);


if ($count_result) {

    $counts = $count_result->fetch_assoc();

    $total_users =
        (int) ($counts["total_users"] ?? 0);

    $total_customers =
        (int) ($counts["total_customers"] ?? 0);

    $total_admins =
        (int) ($counts["total_admins"] ?? 0);
}

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Users | BiteCraft Admin
    </title>


    <!-- ========================================
         BOOTSTRAP
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


    <style>


        /* ========================================
           BODY
        ========================================= */

        body {

            background-color: #f8f9fa;

        }


        /* ========================================
           SIDEBAR
        ======================================== */

        .sidebar {

            width: 250px;

            min-height: 100vh;

            background-color: #212529;

            position: fixed;

            left: 0;

            top: 0;

            padding-top: 20px;

        }


        /* ========================================
           SIDEBAR LOGO
        ======================================== */

        .sidebar-logo {

            color: white;

            font-size: 25px;

            font-weight: bold;

            text-decoration: none;

            display: block;

            padding: 15px 25px;

            margin-bottom: 20px;

        }


        .sidebar-logo:hover {

            color: white;

        }


        .sidebar-logo span {

            color: #ffc107;

        }


        /* ========================================
           SIDEBAR LINKS
        ======================================== */

        .sidebar-link {

            display: block;

            color: #adb5bd;

            text-decoration: none;

            padding: 13px 25px;

            transition: 0.3s;

        }


        .sidebar-link:hover {

            color: white;

            background-color: #343a40;

        }


        .sidebar-link.active {

            color: #212529;

            background-color: #ffc107;

        }


        .sidebar-link i {

            width: 25px;

        }


        /* ========================================
           MAIN CONTENT
        ======================================== */

        .main-content {

            margin-left: 250px;

            min-height: 100vh;

        }


        /* ========================================
           TOPBAR
        ======================================== */

        .topbar {

            background-color: white;

            padding: 15px 30px;

            border-bottom: 1px solid #dee2e6;

        }


        /* ========================================
           STAT CARDS
        ======================================== */

        .stat-card {

            background-color: white;

            border-radius: 15px;

            padding: 25px;

            border: 0;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.05);

            transition: 0.3s;

        }


        .stat-card:hover {

            transform: translateY(-5px);

        }


        /* ========================================
           STAT ICON
        ======================================== */

        .stat-icon {

            width: 55px;

            height: 55px;

            border-radius: 12px;

            display: flex;

            justify-content: center;

            align-items: center;

            font-size: 25px;

            background-color: #fff3cd;

            color: #ffc107;

        }


        /* ========================================
           USERS CARD
        ======================================== */

        .users-card {

            background-color: white;

            border-radius: 15px;

            border: 0;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.05);

            overflow: hidden;

        }


        /* ========================================
           TABLE
        ======================================== */

        .table th {

            white-space: nowrap;

            background-color: #f8f9fa;

        }


        .table td {

            vertical-align: middle;

        }


        /* ========================================
           USER AVATAR
        ======================================== */

        .user-avatar {

            width: 42px;

            height: 42px;

            border-radius: 50%;

            background-color: #fff3cd;

            color: #856404;

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: bold;

        }


        /* ========================================
           SEARCH
        ======================================== */

        .search-box {

            max-width: 420px;

        }


        /* ========================================
           MOBILE
        ======================================== */

        @media (max-width: 768px) {


            .sidebar {

                width: 70px;

            }


            .sidebar-logo {

                font-size: 0;

                text-align: center;

                padding: 15px 5px;

            }


            .sidebar-logo i {

                font-size: 25px;

            }


            .sidebar-link {

                text-align: center;

                padding: 15px 5px;

            }


            .sidebar-link span {

                display: none;

            }


            .sidebar-link i {

                font-size: 20px;

            }


            .main-content {

                margin-left: 70px;

            }


        }


    </style>


</head>


<body>


<!-- ==================================================
     SIDEBAR
================================================== -->


<aside class="sidebar">


    <!-- ========================================
         LOGO
    ========================================= -->

    <a
        href="index.php"
        class="sidebar-logo">

        <i class="bi bi-egg-fried"></i>

        Bite<span>Craft</span>

    </a>


    <!-- ========================================
         DASHBOARD
    ========================================= -->

    <a
        href="index.php"
        class="sidebar-link">

        <i class="bi bi-speedometer2"></i>

        <span>Dashboard</span>

    </a>


    <!-- ========================================
         MENU ITEMS
    ========================================= -->

    <a
        href="menu.php"
        class="sidebar-link">

        <i class="bi bi-egg-fried"></i>

        <span>Menu Items</span>

    </a>


    <!-- ========================================
         ADD MENU
    ========================================= -->

    <a
        href="add-menu.php"
        class="sidebar-link">

        <i class="bi bi-plus-circle"></i>

        <span>Add Menu Item</span>

    </a>


    <!-- ========================================
         ORDERS
    ========================================= -->

    <a
        href="orders.php"
        class="sidebar-link">

        <i class="bi bi-receipt"></i>

        <span>Orders</span>

    </a>


    <!-- ========================================
         RESERVATIONS
    ========================================= -->

    <a
        href="reservations.php"
        class="sidebar-link">

        <i class="bi bi-calendar-check"></i>

        <span>Reservations</span>

    </a>


    <!-- ========================================
         USERS
    ========================================= -->

    <a
        href="users.php"
        class="sidebar-link active">

        <i class="bi bi-people"></i>

        <span>Users</span>

    </a>


    <hr class="border-secondary mx-3">


    <!-- ========================================
         VIEW WEBSITE
    ========================================= -->

    <a
        href="../index.php"
        class="sidebar-link">

        <i class="bi bi-globe"></i>

        <span>View Website</span>

    </a>


    <!-- ========================================
         LOGOUT
    ========================================= -->

    <a
        href="logout.php"
        class="sidebar-link">

        <i class="bi bi-box-arrow-right"></i>

        <span>Logout</span>

    </a>


</aside>



<!-- ==================================================
     MAIN CONTENT
================================================== -->


<main class="main-content">


    <!-- ========================================
         TOPBAR
    ========================================= -->

    <div
        class="topbar d-flex justify-content-between align-items-center">


        <div>

            <h5 class="mb-0 fw-bold">

                Users

            </h5>

        </div>


        <div>

            <i
                class="bi bi-person-circle me-2">
            </i>


            <strong>

                <?php

                echo htmlspecialchars(
                    $admin_name
                );

                ?>

            </strong>

        </div>


    </div>



    <!-- ========================================
         PAGE CONTENT
    ========================================= -->

    <div class="container-fluid p-4">


        <!-- ========================================
             HEADER
        ========================================= -->

        <div class="mb-4">


            <h2 class="fw-bold">

                User Management

            </h2>


            <p class="text-secondary">

                View and manage registered BiteCraft users.

            </p>


        </div>



        <!-- ========================================
             STATISTICS
        ========================================= -->

        <div class="row g-4 mb-5">


            <!-- TOTAL USERS -->

            <div class="col-sm-6 col-xl-4">


                <div class="stat-card">


                    <div
                        class="d-flex justify-content-between">


                        <div>

                            <p
                                class="text-secondary mb-1">

                                Total Users

                            </p>


                            <h2
                                class="fw-bold mb-0">

                                <?php

                                echo $total_users;

                                ?>

                            </h2>

                        </div>


                        <div class="stat-icon">

                            <i
                                class="bi bi-people">
                            </i>

                        </div>


                    </div>


                </div>


            </div>



            <!-- CUSTOMERS -->

            <div class="col-sm-6 col-xl-4">


                <div class="stat-card">


                    <div
                        class="d-flex justify-content-between">


                        <div>

                            <p
                                class="text-secondary mb-1">

                                Customers

                            </p>


                            <h2
                                class="fw-bold mb-0">

                                <?php

                                echo $total_customers;

                                ?>

                            </h2>

                        </div>


                        <div class="stat-icon">

                            <i
                                class="bi bi-person-check">
                            </i>

                        </div>


                    </div>


                </div>


            </div>



            <!-- ADMINS -->

            <div class="col-sm-6 col-xl-4">


                <div class="stat-card">


                    <div
                        class="d-flex justify-content-between">


                        <div>

                            <p
                                class="text-secondary mb-1">

                                Administrators

                            </p>


                            <h2
                                class="fw-bold mb-0">

                                <?php

                                echo $total_admins;

                                ?>

                            </h2>

                        </div>


                        <div class="stat-icon">

                            <i
                                class="bi bi-shield-check">
                            </i>

                        </div>


                    </div>


                </div>


            </div>


        </div>



        <!-- ========================================
             USERS TABLE
        ========================================= -->

        <div class="users-card">


            <!-- ========================================
                 TABLE HEADER
            ========================================= -->

            <div
                class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">


                <div>


                    <h4 class="fw-bold mb-1">

                        <i
                            class="bi bi-people text-warning">
                        </i>

                        All Users

                    </h4>


                    <p
                        class="text-secondary mb-0">

                        Registered customers and administrators.

                    </p>


                </div>



                <!-- ========================================
                     SEARCH
                ========================================= -->

                <form
                    method="GET"
                    action="users.php"
                    class="d-flex search-box">


                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search name, email or role..."
                        value="<?php

                        echo htmlspecialchars(
                            $search
                        );

                        ?>">


                    <button
                        type="submit"
                        class="btn btn-dark ms-2">

                        <i
                            class="bi bi-search">
                        </i>

                    </button>


                    <?php if ($search !== ""): ?>


                        <a
                            href="users.php"
                            class="btn btn-outline-secondary ms-2">

                            <i
                                class="bi bi-x-lg">
                            </i>

                        </a>


                    <?php endif; ?>


                </form>


            </div>



            <!-- ========================================
                 TABLE
            ========================================= -->

            <div class="table-responsive">


                <table
                    class="table table-hover mb-0">


                    <thead>


                        <tr>


                            <th class="px-4">

                                ID

                            </th>


                            <th>

                                User

                            </th>


                            <th>

                                Email

                            </th>


                            <th>

                                Role

                            </th>


                            <th>

                                Registered

                            </th>


                            <th class="text-end px-4">

                                Action

                            </th>


                        </tr>


                    </thead>



                    <tbody>


                    <?php if (
                        $users &&
                        $users->num_rows > 0
                    ): ?>


                        <?php while (
                            $user = $users->fetch_assoc()
                        ): ?>


                            <tr>


                                <!-- ========================================
                                     ID
                                ========================================= -->


                                <td class="px-4">


                                    <strong>

                                        #<?php

                                        echo (int)
                                            $user["id"];

                                        ?>

                                    </strong>


                                </td>



                                <!-- ========================================
                                     USER
                                ========================================= -->


                                <td>


                                    <div
                                        class="d-flex align-items-center gap-3">


                                        <div
                                            class="user-avatar">


                                            <?php

                                            $user_name =
                                                trim(
                                                    $user["name"]
                                                );

                                            echo strtoupper(
                                                substr(
                                                    $user_name,
                                                    0,
                                                    1
                                                )
                                            );

                                            ?>


                                        </div>


                                        <div>


                                            <strong>

                                                <?php

                                                echo htmlspecialchars(
                                                    $user["name"]
                                                );

                                                ?>

                                            </strong>


                                        </div>


                                    </div>


                                </td>



                                <!-- ========================================
                                     EMAIL
                                ========================================= -->


                                <td>


                                    <span
                                        class="text-secondary">


                                        <?php

                                        echo htmlspecialchars(
                                            $user["email"]
                                        );

                                        ?>


                                    </span>


                                </td>



                                <!-- ========================================
                                     ROLE
                                ========================================= -->


                                <td>


                                    <?php if (
                                        $user["role"] === "admin"
                                    ): ?>


                                        <span
                                            class="badge bg-dark">


                                            <i
                                                class="bi bi-shield-check">
                                            </i>


                                            Admin


                                        </span>


                                    <?php else: ?>


                                        <span
                                            class="badge bg-success">


                                            <i
                                                class="bi bi-person">
                                            </i>


                                            Customer


                                        </span>


                                    <?php endif; ?>


                                </td>



                                <!-- ========================================
                                     REGISTERED DATE
                                ========================================= -->


                                <td>


                                    <?php

                                    if (
                                        !empty(
                                            $user["created_at"]
                                        )
                                    ) {

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $user[
                                                    "created_at"
                                                ]
                                            )
                                        );

                                    }

                                    ?>


                                    <br>


                                    <small
                                        class="text-secondary">


                                        <?php

                                        if (
                                            !empty(
                                                $user["created_at"]
                                            )
                                        ) {

                                            echo date(
                                                "h:i A",
                                                strtotime(
                                                    $user[
                                                        "created_at"
                                                    ]
                                                )
                                            );

                                        }

                                        ?>


                                    </small>


                                </td>



                                <!-- ========================================
                                     ACTION
                                ========================================= -->


                                <td
                                    class="text-end px-4">


                                    <a
                                        href="view-user.php?id=<?php echo (int) $user["id"]; ?>"
                                        class="btn btn-sm btn-primary">


                                        <i
                                            class="bi bi-eye">
                                        </i>


                                        View


                                    </a>


                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <!-- ========================================
                             NO USERS
                        ========================================= -->


                        <tr>


                            <td
                                colspan="6"
                                class="text-center py-5">


                                <i
                                    class="bi bi-people"
                                    style="
                                        font-size: 55px;
                                        color: #adb5bd;
                                    ">
                                </i>


                                <h5 class="mt-3">

                                    No Users Found

                                </h5>


                                <p
                                    class="text-secondary mb-0">


                                    <?php if (
                                        $search !== ""
                                    ): ?>


                                        No users match your search.


                                    <?php else: ?>


                                        No registered users available.


                                    <?php endif; ?>


                                </p>


                            </td>


                        </tr>


                    <?php endif; ?>


                    </tbody>


                </table>


            </div>


        </div>


    </div>


</main>



<!-- ========================================
     BOOTSTRAP JS
======================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>


<?php

$stmt->close();

?>

