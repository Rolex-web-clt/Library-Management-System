<?php
session_start();
require("functions.php");

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","lms");
if (!$conn) {
    die("Database connection failed");
}

$error = "";

if (isset($_POST['add_author'])) {

    $author_name = trim($_POST['author_name']);
    $author_name = mysqli_real_escape_string($conn, $author_name);

    // Validation
    if (empty($author_name)) {
        $error = "Author name is required.";
    } 
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $author_name)) {
        $error = "Author name should contain only letters and spaces.";
    } 
    else {

        // Check duplicate
        $check_query = "SELECT * FROM authors WHERE author_name='$author_name'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Author already exists.";
        } 
        else {
            $insert_query = "INSERT INTO authors (author_name) VALUES ('$author_name')";
            mysqli_query($conn, $insert_query);

            echo "<script>
                alert('Author added successfully');
                window.location.href='admin_dashboard.php';
            </script>";
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Author</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- TOP NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="admin_dashboard.php">📚 LMS</a>

        <div class="text-white ms-auto me-3">
            <strong><?php echo $_SESSION['name']; ?></strong><br>
            <small><?php echo $_SESSION['email']; ?></small>
        </div>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">My Profile</a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
                    <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
                    <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!-- SECOND NAV -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <ul class="navbar-nav mx-auto gap-3">
            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Books</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="add_book.php">Add Book</a></li>
                    <li><a class="dropdown-item" href="manage_book.php">Manage Books</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Categories</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="add_cat.php">Add Category</a></li>
                    <li><a class="dropdown-item" href="manage_cat.php">Manage Category</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Authors</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item active" href="add_author.php">Add Author</a></li>
                    <li><a class="dropdown-item" href="manage_author.php">Manage Authors</a></li>
                </ul>
            </li>

            <li class="nav-item"><a class="nav-link" href="issue_book.php">Issue Book</a></li>
        </ul>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">➕ Add New Author</h5>
                </div>

                <div class="card-body">
                  <form method="post" id="authorForm">
                        <div class="mb-3">
                            <label class="form-label">Author Name</label>
                            <input type="text" name="author_name" class="form-control" required>
                        </div>

                        <button type="submit" name="add_author" class="btn btn-success w-100">
                            Add Author
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Your Form Validation JS -->
<script>
document.getElementById("authorForm").addEventListener("submit", function(e) {

    let authorName = document.querySelector("input[name='author_name']").value.trim();

    // Only letters and spaces allowed
    let pattern = /^[A-Za-z\s]+$/;

    if (authorName === "") {
        alert("Author name cannot be empty!");
        e.preventDefault();   // stop form submission
        return;
    }

    if (!pattern.test(authorName)) {
        alert("Invalid Author Name! Only letters and spaces are allowed.");
        e.preventDefault();   // stop form submission
        return;
    }

});
</script>

</body>
</html>
