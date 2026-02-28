<?php
session_start();

$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed");
}

if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    // Check if author has books
    $check = mysqli_query($connection, "
        SELECT * FROM books WHERE author_id = $id
    ");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('❌ Author cannot be deleted. Books exist under this author.');</script>";
        echo "<script>window.location='manage_author.php';</script>";
        exit;
    }

    // Safe delete
if (mysqli_query($connection, "DELETE FROM authors WHERE author_id = $id")) {

    echo "<script>
            alert('✅ Author deleted successfully.');
            window.location = 'manage_author.php';
          </script>";
    exit;
}

}

?>
