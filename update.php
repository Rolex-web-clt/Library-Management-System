<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("DB Connection Failed: " . mysqli_connect_error());
}

try {
    $user_id = $_SESSION['id'];

    // Sanitize and validate input
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $mobile  = trim($_POST['mobile']);
    $address = trim($_POST['address']);

    if (empty($name) || empty($email) || empty($mobile) || empty($address)) {
        throw new Exception("All fields are required!");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format!");
    }

    if (!preg_match("/^[0-9]{7,15}$/", $mobile)) {
        throw new Exception("Invalid mobile number! Only digits allowed, 7-15 characters.");
    }

    
$name = trim($_POST['name']);

if (empty($name)) {
    throw new Exception("Name is required!");
}

if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
    throw new Exception("Name must contain letters only and no dots or numbers!");
}

if (strlen($name) < 3 || strlen($name) > 50) {
    throw new Exception("Name must be between 3 and 50 characters!");
}

    if (strlen($address) < 5 || strlen($address) > 200) {
        throw new Exception("Address must be between 5 and 200 characters!");
    }

    // Check if email is already used by another user
    $stmt = mysqli_prepare($connection, "SELECT id FROM users WHERE email=? AND id<>?");
    mysqli_stmt_bind_param($stmt, "si", $email, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        throw new Exception("Email already exists!");
    }

    // Update user profile using prepared statement
    $update_stmt = mysqli_prepare($connection, "
        UPDATE users 
        SET name=?, email=?, mobile=?, address=? 
        WHERE id=?
    ");
    mysqli_stmt_bind_param($update_stmt, "ssssi", $name, $email, $mobile, $address, $user_id);
    mysqli_stmt_execute($update_stmt);

    // Update session
    $_SESSION['name']  = $name;
    $_SESSION['email'] = $email;

    echo "<script>
            alert('✅ Profile updated successfully');
            window.location.href='user_dashboard.php';
          </script>";

} catch (Exception $e) {
    $error_msg = addslashes($e->getMessage());
    echo "<script>
            alert('❌ $error_msg');
            window.location.href='edit_profile.php';
          </script>";
}
?>