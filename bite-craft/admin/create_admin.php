<?php

require_once "../config/database.php";

$email = "admin@bitecraft.com";
$password = "Admin@123";

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);


// Check admin

$stmt = $conn->prepare(
    "SELECT id FROM users WHERE email = ? LIMIT 1"
);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 1) {

    $admin = $result->fetch_assoc();

    // Update password and role

    $update = $conn->prepare(
        "UPDATE users
         SET password = ?, role = 'admin'
         WHERE id = ?"
    );

    $update->bind_param(
        "si",
        $hashedPassword,
        $admin["id"]
    );


    if ($update->execute()) {

        echo "<h2>Admin updated successfully!</h2>";

        echo "Email: admin@bitecraft.com<br>";
        echo "Password: Admin@123<br>";

    } else {

        echo "Update failed: " . $update->error;

    }

} else {

    echo "Admin account not found.";

}

?>