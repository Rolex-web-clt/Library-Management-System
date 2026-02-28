<?php
session_start();

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) die("Database connection failed!");

if (isset($_POST['update_password'])) {

    // Get form data
    $current_password = trim($_POST['current_password']);
    $new_password     = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    $admin_id = $_SESSION['admin_id'];

    // Server-side validation
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required!'); window.location.href='change_password.php';</script>";
        exit;
    }
    if (strlen($new_password) < 6) {
        echo "<script>alert('New password must be at least 6 characters long!'); window.location.href='change_password.php';</script>";
        exit;
    }
    if ($new_password !== $confirm_password) {
        echo "<script>alert('New password and confirm password do not match!'); window.location.href='change_password.php';</script>";
        exit;
    }

    // Fetch old password from DB
    $query  = "SELECT password FROM admins WHERE id = '$admin_id'";
    $result = mysqli_query($connection, $query);
    $row    = mysqli_fetch_assoc($result);

    if ($row['password'] != $current_password) {
        echo "<script>alert('Current password is incorrect!'); window.location.href='change_password.php';</script>";
        exit;
    }

    // Update password
    $update_query = "UPDATE admins SET password = '$new_password' WHERE id = '$admin_id'";
    if (mysqli_query($connection, $update_query)) {
        echo "<script>alert('Password updated successfully!'); window.location.href='admin_dashboard.php';</script>";
        exit;
    } else {
        echo "<script>alert('Password update failed!'); window.location.href='change_password.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Change Password | LMS Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center fw-bold">
                    🔐 Change Password
                </div>
                <div class="card-body">
                    <form id="passwordForm" method="POST">
                        <div class="mb-3">
                            <label>Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>New Password</label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                        </div>

                        <div class="mb-3">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" name="update_password" class="btn btn-success w-100">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Validation -->
<script>
document.getElementById("passwordForm").addEventListener("submit", function(e){
    const current = document.querySelector("input[name='current_password']").value.trim();
    const newPass = document.querySelector("input[name='new_password']").value.trim();
    const confirm = document.querySelector("input[name='confirm_password']").value.trim();

    if(!current || !newPass || !confirm){
        alert("All fields are required!");
        e.preventDefault(); return;
    }
    if(newPass.length < 6){
        alert("New password must be at least 6 characters!");
        e.preventDefault(); return;
    }
    if(newPass !== confirm){
        alert("New password and confirm password do not match!");
        e.preventDefault(); return;
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>