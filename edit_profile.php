<?php
session_start();

// Redirect if user not logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed!");
}

$user_id = $_SESSION['id'];

// Fetch user details
$query = "SELECT name, email, mobile, address FROM users WHERE id = '$user_id'";
$result = mysqli_query($connection, $query);
$user = mysqli_fetch_assoc($result);

// Handle form submission
if (isset($_POST['update'])) {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $mobile  = trim($_POST['mobile']);
    $address = trim($_POST['address']);

    // Server-side validation
    $error = "";
    if (empty($name) || empty($email) || empty($mobile)) {
        $error = "Name, Email, and Mobile are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $mobile)) {
        $error = "Mobile number must be 10-15 digits!";
    }

    if ($error == "") {
        $name    = mysqli_real_escape_string($connection, $name);
        $email   = mysqli_real_escape_string($connection, $email);
        $mobile  = mysqli_real_escape_string($connection, $mobile);
        $address = mysqli_real_escape_string($connection, $address);

        $update_query = "UPDATE users 
                         SET name='$name', email='$email', mobile='$mobile', address='$address' 
                         WHERE id='$user_id'";

        if (mysqli_query($connection, $update_query)) {
            echo "<script>
                    alert('Profile updated successfully!');
                    window.location.href='user_dashboard.php';
                  </script>";
            exit;
        } else {
            $error = "Update failed: " . mysqli_error($connection);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Profile | LMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">📚 LMS</a>
        <div class="text-white ms-auto">
            Welcome, <strong><?php echo $_SESSION['name']; ?></strong>
            (<?php echo $_SESSION['email']; ?>)
        </div>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">My Profile</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
                    <li><a class="dropdown-item active" href="edit_profile.php">Edit Profile</a></li>
                    <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    Edit Profile
                </div>
                <div class="card-body">
                    <form id="profileForm" method="post">

                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?php echo $user['name']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo $user['email']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Mobile</label>
                            <input type="text" name="mobile" class="form-control" 
                                   value="<?php echo $user['mobile']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Address</label>
                            <textarea name="address" class="form-control"><?php echo $user['address']; ?></textarea>
                        </div>

                        <button type="submit" name="update" class="btn btn-success w-100">
                            Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Validation -->
<script>
document.getElementById("profileForm").addEventListener("submit", function(e) {
    const name = document.querySelector("input[name='name']").value.trim();
    const email = document.querySelector("input[name='email']").value.trim();
    const mobile = document.querySelector("input[name='mobile']").value.trim();

    if (!name || !email || !mobile) {
        alert("Name, Email, and Mobile are required!");
        e.preventDefault();
        return;
    }

       // Name validation: letters and spaces only
    const namePattern = /^[a-zA-Z\s]{3,50}$/;
    if (!namePattern.test(name)) {
        alert("Name must contain letters only (no dots, numbers, or special characters) and be 3-50 characters long!");
        e.preventDefault();
        return;
    }
    // Email validation
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
    if (!emailPattern.test(email)) {
        alert("Invalid email format!");
        e.preventDefault();
        return;
    }

    // Mobile validation: 10-15 digits
    const mobilePattern = /^[0-9]{10,15}$/;
    if (!mobilePattern.test(mobile)) {
        alert("Mobile must be 10-15 digits!");
        e.preventDefault();
        return;
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>