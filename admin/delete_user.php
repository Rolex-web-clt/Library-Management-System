<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) die("Database connection failed");

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Delete dependent issued books first
    mysqli_query($connection, "DELETE FROM issued_books WHERE user_id = $id");

    // Now delete user
    mysqli_query($connection, "DELETE FROM users WHERE id = $id");

    header("Location: manage_user.php");
    exit;
}
?>