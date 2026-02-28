<?php
session_start();

// Redirect if admin not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php"); // admin login page
    exit;
}

require("functions.php"); // optional: database connection functions

$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) die("Database connection failed");

$error = "";
$success = "";

// Server-side validation and update
if(isset($_POST['update_password'])) {

    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Basic validation
    if(empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif(strlen($new_password) < 6) {
        $error = "New password must be at least 6 characters long.";
    } elseif($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match.";
    } else {
        // Verify current password from DB
        $admin_id = (int)$_SESSION['admin_id'];
        $query = "SELECT password FROM admins WHERE admin_id = $admin_id";
        $result = mysqli_query($connection, $query);
        if($row = mysqli_fetch_assoc($result)) {
            if(!password_verify($current_password, $row['password'])) {
                $error = "Current password is incorrect.";
            } else {
                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update = "UPDATE admins SET password='$hashed_password' WHERE admin_id=$admin_id";
                if(mysqli_query($connection, $update)) {
                    $success = "Password updated successfully!";
                } else {
                    $error = "Database error: ".mysqli_error($connection);
                }
            }
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin | Change Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="admin_dashboard.php">📚 LMS Admin Panel</a>
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item text-white me-3">Welcome, <strong><?php echo $_SESSION['name']; ?></strong></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">My Account</a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="admin_profile.php">Profile</a></li>
                    <li><a class="dropdown-item active" href="admin_change_password.php">Change Password</a></li>
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
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center fw-bold">🔐 Change Admin Password</div>
                <div class="card-body">

                    <!-- Server-side messages -->
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if(!empty($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" id="passwordForm">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" name="update_password" class="btn btn-success w-100">Update Password</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById("passwordForm").addEventListener("submit", function(e) {
    let current = document.querySelector("input[name='current_password']").value.trim();
    let newPass = document.querySelector("input[name='new_password']").value.trim();
    let confirmPass = document.querySelector("input[name='confirm_password']").value.trim();

    if(current === "" || newPass === "" || confirmPass === "") {
        alert("All fields are required!");
        e.preventDefault();
        return;
    }

    if(newPass.length < 6) {
        alert("New password must be at least 6 characters!");
        e.preventDefault();
        return;
    }

    if(newPass !== confirmPass) {
        alert("New password and confirm password do not match!");
        e.preventDefault();
        return;
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>