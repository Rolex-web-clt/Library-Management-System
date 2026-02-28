<?php
// Connect to database
$connection = mysqli_connect("localhost","root","","lms");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Validate and get book_number from URL
if (isset($_GET['bn']) && is_numeric($_GET['bn'])) {
    $book_number = intval($_GET['bn']);

    // Delete query
    $query = "DELETE FROM books WHERE book_number = $book_number";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        echo "<script type='text/javascript'>
                alert('Book deleted successfully...');
                window.location.href = 'manage_book.php';
              </script>";
    } else {
        echo "<script type='text/javascript'>
                alert('Error deleting book...');
                window.location.href = 'manage_book.php';
              </script>";
    }
} else {
    echo "<script type='text/javascript'>
            alert('Invalid book number...');
            window.location.href = 'manage_book.php';
          </script>";
}
?>
