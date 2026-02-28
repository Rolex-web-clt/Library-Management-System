<?php
require("functions.php");
session_start();

// Redirect if admin not logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed");
}

// Fetch admin info
$query = "SELECT * FROM admins WHERE email = '$_SESSION[email]'";
$query_run = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($query_run);
$name = $row['name'];
$email = $row['email'];

$error = "";
$success = "";

// INSERT BOOK
if (isset($_POST['add_book'])) {

    $book_name   = trim(mysqli_real_escape_string($connection, $_POST['book_name']));
    $author_id   = (int)$_POST['author_id'];
    $cat_id      = (int)$_POST['cat_id'];
    $book_number = (int)$_POST['book_number'];
    $price       = (float)$_POST['price'];

    // Server-side validation
    if (empty($book_name)) {
        $error = "Book name is required.";
    } elseif (!preg_match("/^[A-Za-z0-9\s\.,'-]+$/", $book_name)) {
        $error = "Invalid book name. Only letters, numbers, spaces, and basic punctuation allowed.";
    } elseif ($author_id <= 0) {
        $error = "Please select an author.";
    } elseif ($cat_id <= 0) {
        $error = "Please select a category.";
    } elseif ($book_number <= 0) {
        $error = "Book number must be a positive integer.";
    } elseif ($price <= 0) {
        $error = "Price must be greater than 0.";
    } else {
        // Insert into DB
        $query = "
            INSERT INTO books 
            (book_name, author_id, cat_id, book_number, price)
            VALUES
            ('$book_name', $author_id, $cat_id, $book_number, $price)
        ";
        if (mysqli_query($connection, $query)) {
            $success = "Book Added Successfully!";
        } else {
            $error = "Database Error: " . mysqli_error($connection);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book | LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand"><a class="navbar-brand fw-bold" href="admin_dashboard.php">Library Management System</a></span>
        <span class="text-white">Welcome, <?php echo $name; ?></span>
    </div>
</nav>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">Add New Book</div>
        <div class="card-body">

            <!-- Show Server Messages -->
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if(!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="post" id="bookForm">

                <div class="mb-3">
                    <label class="form-label">Book Name</label>
                    <input type="text" name="book_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Author</label>
                    <select name="author_id" class="form-control" required>
                        <option value="">-- Select Author --</option>
                        <?php
                        $authors = mysqli_query($connection, "SELECT * FROM authors");
                        while ($a = mysqli_fetch_assoc($authors)) {
                            echo "<option value='{$a['author_id']}'>{$a['author_name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="cat_id" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        <?php
                        $categories = mysqli_query($connection, "SELECT * FROM categories");
                        while ($c = mysqli_fetch_assoc($categories)) {
                            echo "<option value='{$c['cat_id']}'>{$c['cat_name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Book Number</label>
                    <input type="number" name="book_number" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>

                <button type="submit" name="add_book" class="btn btn-primary w-100">Add Book</button>

            </form>
        </div>
    </div>
</div>

<!-- JS Validation -->
<script>
document.getElementById("bookForm").addEventListener("submit", function(e) {
    let bookName = document.querySelector("input[name='book_name']").value.trim();
    let bookNumber = document.querySelector("input[name='book_number']").value.trim();
    let price = document.querySelector("input[name='price']").value.trim();
    let author = document.querySelector("select[name='author_id']").value;
    let category = document.querySelector("select[name='cat_id']").value;

    let namePattern = /^[A-Za-z0-9\s'-]+$/;

    if (bookName === "") {
        alert("Book name cannot be empty!");
        e.preventDefault();
        return;
    }
    if (!namePattern.test(bookName)) {
        alert("Invalid Book Name! Only letters, numbers, spaces, and basic punctuation allowed.");
        e.preventDefault();
        return;
    }
    if (author === "") {
        alert("Please select an author.");
        e.preventDefault();
        return;
    }
    if (category === "") {
        alert("Please select a category.");
        e.preventDefault();
        return;
    }
    if (bookNumber <= 0) {
        alert("Book number must be a positive integer.");
        e.preventDefault();
        return;
    }
    if (price <= 0) {
        alert("Price must be greater than 0.");
        e.preventDefault();
        return;
    }
});
</script>

</body>
</html>