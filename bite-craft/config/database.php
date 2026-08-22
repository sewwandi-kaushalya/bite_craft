<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "bite_craft";
$port = 3307;

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database,
    $port
);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

?>