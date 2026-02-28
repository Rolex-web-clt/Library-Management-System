<?php
	require("functions.php");
	session_start();
	#fetch data from database
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	$name = "";
	$email = "";
	$mobile = "";
	$query = "select * from admins where email = '$_SESSION[email]'";
	$query_run = mysqli_query($connection,$query);
	while ($row = mysqli_fetch_assoc($query_run)){
		$name = $row['name'];
		$email = $row['email'];
		$mobile = $row['mobile'];
	}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Profile</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background-color: #f8f9fa;
    }

    .card-hover:hover {
      transform: translateY(-5px);
      transition: 0.3s ease;
    }

    .notice-bar {
      background-color: #e9f7fe;
      color: #0c5460;
      padding: 10px;
      text-align: center;
      border-radius: 5px;
      margin: 20px 0;
      font-weight: 500;
    }

    h4 {
      color: #0d6efd;
      font-weight: 600;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="admin_dashboard.php">📚 LMS</a>

    <div class="d-flex ms-auto text-light">
      <span class="navbar-text me-3">👋 Welcome: <strong><?php echo $_SESSION['name'];?></strong></span>
      <span class="navbar-text">📧 Email: <strong><?php echo $_SESSION['email'];?></strong></span>

      <ul class="navbar-nav ms-3">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">My Profile</a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
            <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
            <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="../logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Notice Bar -->
<div class="container">
  <div class="notice-bar">
    📢 Library opens at <strong>9:00 AM</strong> and closes at <strong>5:00 PM</strong> (Saturday Closed)
  </div>
</div>

<!-- Admin Profile Card -->
<div class="container mb-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg card-hover p-4">
        <h4 class="text-center mb-4">👤 Admin Profile Details</h4>
        <form>
          <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" value="<?php echo $name;?>" disabled>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="text" class="form-control" value="<?php echo $email;?>" disabled>
          </div>
          <div class="mb-3">
            <label for="mobile" class="form-label">Mobile:</label>
            <input type="text" class="form-control" value="<?php echo $mobile;?>" disabled>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


</body>
</html>
