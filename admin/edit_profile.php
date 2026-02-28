<?php
session_start();
require("functions.php");

$connection = mysqli_connect("localhost","root","","lms");
if (!$connection) die("Database connection failed!");

$name = $email = $mobile = "";
$error = "";

// Fetch admin info
if (isset($_SESSION['email'])) {
    $query = "SELECT * FROM admins WHERE email = '$_SESSION[email]'";
    $query_run = mysqli_query($connection,$query);
    if ($row = mysqli_fetch_assoc($query_run)) {
        $name   = $row['name'];
        $email  = $row['email'];
        $mobile = $row['mobile'];
    }
}

// Handle form submit
if (isset($_POST['update'])) {
    $name   = trim($_POST['name']);
    $email  = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);

    // Server-side validation
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error = "Name can contain only letters and spaces.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (!preg_match("/^[0-9]{7,15}$/", $mobile)) {
        $error = "Mobile number must contain only digits (7-15 characters).";
    } else {
        // Update admin profile
        $update = "UPDATE admins SET name='$name', email='$email', mobile='$mobile' WHERE email='$_SESSION[email]'";
        if (mysqli_query($connection, $update)) {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            echo "<script>alert('Profile updated successfully'); window.location='admin_dashboard.php';</script>";
            exit;
        } else {
            $error = "Update failed: ".mysqli_error($connection);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Admin Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="admin_dashboard.php">📚 LMS</a>
        <div class="ms-auto text-white me-3">
            👋 Welcome, <strong><?php echo $_SESSION['name']; ?></strong><br>
            📧 <?php echo $_SESSION['email']; ?>
        </div>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">My Profile</a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item active">Edit Profile</a></li>
                    <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center fw-bold">
                    Edit Admin Profile
                </div>
                <div class="card-body">

                    <!-- Server-side error -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="post" id="adminForm">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="mobile" class="form-control" value="<?php echo $mobile; ?>" required>
                        </div>

                        <button type="submit" name="update" class="btn btn-success w-100">
                            💾 Update Profile
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Validation -->
<script>
document.getElementById("adminForm").addEventListener("submit", function(e){
    let name = document.querySelector("input[name='name']").value.trim();
    let email = document.querySelector("input[name='email']").value.trim();
    let mobile = document.querySelector("input[name='mobile']").value.trim();

    let namePattern = /^[a-zA-Z\s]+$/;
    let mobilePattern = /^[0-9]{7,15}$/;
    
    if(!namePattern.test(name)){
        alert("Name can contain only letters and spaces.");
        e.preventDefault(); return;
    }
    if(!email.includes("@") || !email.includes(".")){
        alert("Please enter a valid email.");
        e.preventDefault(); return;
    }
    if(!mobilePattern.test(mobile)){
        alert("Mobile number must be 7-15 digits only.");
        e.preventDefault(); return;
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>