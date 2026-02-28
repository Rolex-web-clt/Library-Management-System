<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed!");
}

if (isset($_POST['update'])) {

    $user_id = $_SESSION['id'];
    $old_password = trim($_POST['old_password']);
    $new_password = trim($_POST['new_password']);

    // Server-side validation
    if (empty($old_password) || empty($new_password)) {
        echo "<script>alert('All fields are required!'); window.location.href='change_password.php';</script>";
        exit;
    }

    if (strlen($new_password) < 6) {
        echo "<script>alert('New password must be at least 6 characters!'); window.location.href='change_password.php';</script>";
        exit;
    }

    // Fetch current password from DB
    $query  = "SELECT password FROM users WHERE id = '$user_id'";
    $result = mysqli_query($connection, $query);
    $row    = mysqli_fetch_assoc($result);

    if ($row['password'] != $old_password) {
        echo "<script>alert('Old password is incorrect!'); window.location.href='change_password.php';</script>";
        exit;
    }

    // Update password
    $update_query = "UPDATE users SET password = '$new_password' WHERE id = '$user_id'";
    if (mysqli_query($connection, $update_query)) {
        echo "<script>alert('Password updated successfully!'); window.location.href='user_dashboard.php';</script>";
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
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Change Password | LMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="user_dashboard.php">Library Management System</a>
        <div class="text-white ms-auto">
            Welcome: <strong><?php echo $_SESSION['name']; ?></strong> | 
            Email: <strong><?php echo $_SESSION['email']; ?></strong>
        </div>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">My Profile</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
                    <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
                    <li><a class="dropdown-item active" href="change_password.php">Change Password</a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <div class="alert alert-info text-center">
        Library opens at <strong>9:00 AM</strong> and closes at <strong>5:00 PM</strong>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    Change Password
                </div>
                <div class="card-body">
                    <form id="passwordForm" method="post">

                        <div class="mb-3">
                            <label>Old Password</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>New Password</label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                        </div>

                        <div class="mb-3">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" name="update" class="btn btn-success w-100">Update Password</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Validation -->
<script>
document.getElementById("passwordForm").addEventListener("submit", function(e){
    const oldPass = document.querySelector("input[name='old_password']").value.trim();
    const newPass = document.querySelector("input[name='new_password']").value.trim();
    const confirm = document.querySelector("input[name='confirm_password']").value.trim();

    if(!oldPass || !newPass || !confirm){
        alert("All fields are required!");
        e.preventDefault();
        return;
    }

    if(newPass.length < 6){
        alert("New password must be at least 6 characters!");
        e.preventDefault();
        return;
    }

    if(newPass !== confirm){
        alert("New password and confirm password do not match!");
        e.preventDefault();
        return;
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
