
<?php
session_start();
if (isset($_POST['login'])) {

    $connection = mysqli_connect("localhost", "root", "", "lms");

    if (!$connection) {
        die("Database connection failed");
    }

    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Prepared Statement
    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt  = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        $_SESSION['id']    = $row['id'];
        $_SESSION['name']  = $row['name'];
        $_SESSION['email'] = $row['email'];

        header("Location: user_dashboard.php");
        exit;

    } else {
        echo "<div class='alert alert-danger text-center'>
                ❌ Invalid Email or Password
              </div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>LMS</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<style type="text/css">
	#main_content{
		padding: 50px;
		background-color: whitesmoke;
	}
	#side_bar{
		background-color: whitesmoke;
		padding: 50px;
		width: 300px;
		height: 450px;
	}

  .navbar .nav-link {
    transition: 0.3s ease;
  }

  body {
    background-color: #f8f9fa;
  }

  .card:hover {
    transform: translateY(-5px);
    transition: 0.3s ease;
  }



	
</style>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      📚 Library Management System
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
      <ul class="navbar-nav gap-2">
        <li class="nav-item">
          <a class="btn btn-outline-light btn-sm px-3" href="admin/index.php">Admin Login</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light btn-sm px-3" href="signup.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light btn-sm px-3" href="index.php">Login</a>
        </li>

      </ul>
    </div>
  </div>
</nav>
<br>
	<div class="alert alert-info text-center fw-semibold rounded-0">
  📢 This is Library Management System. Library opens at <b>9:00 AM</b> and closes at <b>5:00 PM</b>
</div>
<div class="container my-4">
  <div class="row g-4">

    <!-- Sidebar -->
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">

          <h5 class="card-title text-primary">⏰ Library Timing</h5>
          <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item">Opening: 9:00 AM</li>
            <li class="list-group-item">Closing: 5:00 PM</li>
            <li class="list-group-item text-danger">(Saturday Off)</li>
          </ul>

        <h5 class="text-primary mt-4">✨ Our Facilities & Services</h5>
 

<div class="row g-3">
  <div class="col-6">
    <div class="card text-center shadow-sm h-100">
      <div class="card-body">
        <h4>📚</h4>
        <p class="mb-0 fw-semibold">Fully Furnished</p>
      </div>
    </div>
  </div>

  <div class="col-6">
    <div class="card text-center shadow-sm h-100">
      <div class="card-body">
        <h4>📶</h4>
        <p class="mb-0 fw-semibold">Free Wi-Fi</p>
      </div>
    </div>
  </div>

  <div class="col-6">
    <div class="card text-center shadow-sm h-100">
      <div class="card-body">
        <h4>📰</h4>
        <p class="mb-0 fw-semibold">Newspapers</p>
      </div>
    </div>
  </div>

  <div class="col-6">
    <div class="card text-center shadow-sm h-100">
      <div class="card-body">
        <h4>💬</h4>
        <p class="mb-0 fw-semibold">Discussion Room</p>
      </div>
    </div>
  </div>

  <div class="col-6">
    <div class="card text-center shadow-sm h-100">
      <div class="card-body">
        <h4>🚰</h4>
        <p class="mb-0 fw-semibold">RO Water</p>
      </div>
    </div>
  </div>

  <div class="col-6">
    <div class="card text-center shadow-sm h-100">
      <div class="card-body">
        <h4>🌿</h4>
        <p class="mb-0 fw-semibold">Peaceful Environment</p>
      </div>
    </div>
  </div>
</div>


        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-8">
      <div class="card shadow-lg">
        <div class="card-body p-4">

          <h3 class="text-center mb-4 fw-bold">
            👤 User Login
          </h3>

          <form action="" method="post">

            <div class="mb-3">
              <label class="form-label">Email ID</label>
              <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <div class="d-flex justify-content-between align-items-center">
              <button type="submit" name="login" class="btn btn-primary px-4">
                Login
              </button>
              <a href="signup.php" class="text-decoration-none">
                Not registered yet?
              </a>
            </div>

          </form>

        </div>
      </div>
    </div>

  </div>
</div>


		</div>
	</div>
</body>
</html>



