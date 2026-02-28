<?php
require("functions.php");
session_start();

// Redirect if admin not logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

$connection = mysqli_connect("localhost","root","","lms");

$name = $email = $mobile = "";
$query = "SELECT * FROM admins WHERE email = '$_SESSION[email]'";
$query_run = mysqli_query($connection,$query);
if ($row = mysqli_fetch_assoc($query_run)) {
    $name = $row['name'];
    $email = $row['email'];
    $mobile = $row['mobile'];
}

$error = "";
$success = "";

// Server-side validation & insertion
if(isset($_POST['add_cat'])) {
    $cat_name = trim(mysqli_real_escape_string($connection, $_POST['cat_name']));

    if(empty($cat_name)) {
        $error = "Category name is required.";
    } elseif(!preg_match("/^[a-zA-Z0-9\s]+$/", $cat_name)) {
        $error = "Invalid category name. Only letters, numbers and spaces allowed.";
    } else {
        // Check duplicate
        $check_query = "SELECT * FROM categories WHERE cat_name='$cat_name'";
        $check_result = mysqli_query($connection, $check_query);
        if(mysqli_num_rows($check_result) > 0) {
            $error = "Category already exists.";
        } else {
            $query = "INSERT INTO categories (cat_name) VALUES ('$cat_name')";
            if(mysqli_query($connection, $query)) {
                $success = "Category added successfully!";
            } else {
                $error = "Database error: " . mysqli_error($connection);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add New Category - LMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg mt-4">
                <div class="card-header text-center">Add New Category</div>
                <div class="card-body">

                    <!-- Server-side messages -->
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if(!empty($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form action="" method="post" id="catForm">
                        <div class="mb-3">
                            <label for="cat_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="cat_name" id="cat_name" placeholder="Enter category name" required>
                        </div>
                        <button type="submit" name="add_cat" class="btn btn-primary w-100">Add Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Validation -->
<script>
document.getElementById("catForm").addEventListener("submit", function(e) {
    let catName = document.getElementById("cat_name").value.trim();
    let pattern = /^[a-zA-Z0-9\s]+$/;

    if(catName === "") {
        alert("Category name cannot be empty!");
        e.preventDefault();
        return;
    }

    if(!pattern.test(catName)) {
        alert("Invalid category name! Only letters, numbers and spaces allowed.");
        e.preventDefault();
        return;
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php

if(isset($_POST['add_cat'])) {

    $cat_name = mysqli_real_escape_string($connection, $_POST['cat_name']);

    $query = "INSERT INTO categories (cat_name) VALUES ('$cat_name')";
    $query_run = mysqli_query($connection, $query);

    if($query_run) {
        echo "<script>alert('Category added successfully'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error adding category');</script>";
    }
}


?>
