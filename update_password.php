<?php
session_start();

// Redirect if user not logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("DB Connection Failed: " . mysqli_connect_error());
}

if (isset($_POST['update'])) {

    $user_id        = $_SESSION['id'];
    $old_password   = trim($_POST['old_password']);
    $new_password   = trim($_POST['new_password']);
    $confirm_pass   = trim($_POST['confirm_password']);

    // Server-side validation
    if (empty($old_password) || empty($new_password) || empty($confirm_pass)) {
        $error = "All fields are required!";
    } elseif ($new_password !== $confirm_pass) {
        $error = "New password and confirm password do not match!";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        // Fetch current password hash from DB
        $stmt = mysqli_prepare($connection, "SELECT password FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row && password_verify($old_password, $row['password'])) {
            // Hash the new password
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in DB
            $update_stmt = mysqli_prepare($connection, "UPDATE users SET password=? WHERE id=?");
            mysqli_stmt_bind_param($update_stmt, "si", $new_hash, $user_id);
            mysqli_stmt_execute($update_stmt);

            echo "<script>
                    alert('Password updated successfully!');
                    window.location = 'user_dashboard.php';
                  </script>";
            exit;
        } else {
            $error = "Old password is incorrect!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Change Password | LMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    🔐 Change Password
                </div>
                <div class="card-body">

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                    <?php endif; ?>

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

<script>
document.getElementById("passwordForm").addEventListener("submit", function(e) {
    const newPass = document.querySelector("input[name='new_password']").value;
    const confirmPass = document.querySelector("input[name='confirm_password']").value;

    if (newPass.length < 6) {
        alert("New password must be at least 6 characters!");
        e.preventDefault();
    } else if (newPass !== confirmPass) {
        alert("New password and confirm password do not match!");
        e.preventDefault();
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>