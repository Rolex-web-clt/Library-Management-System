<?php
	session_start();
	
?>
<!DOCTYPE html>
<html>
<head>
  <title>LMS | Admin Login</title>
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

    #side_bar, #main_content {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 8px;
    }

    h3 {
      color: #0d6efd;
      font-weight: 600;
    }
  </style>
</head>
<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <!-- Brand -->
    <a class="navbar-brand fw-bold" href="index.php">📚 Library Management System (LMS)</a>

    <!-- Navbar items -->
    <ul class="navbar-nav ms-auto">
      <li class="nav-item me-2">
        <a class="nav-link btn btn-outline-success px-3" href="index.php">Admin Login</a>
      </li>
      <li class="nav-item me-2">
        <a class="nav-link btn btn-outline-primary px-3" href="../signup.php">Register</a>
      </li>
      <li class="nav-item">
        <a class="nav-link btn btn-outline-success px-3" href="../index.php">Login</a>
      </li>
    </ul>
  </div>
</nav>


<!-- Notice Bar -->
<div class="container">
  <div class="notice-bar">
    📢 Library opens at <strong>9:00 AM</strong> and closes at <strong>5:00 PM</strong>.
  </div>
</div>

<!-- Main Content -->
<div class="container">
  <div class="row g-4">

    <!-- Sidebar -->
    <div class="col-md-4">
      <div id="side_bar" class="shadow-sm card-hover">
        <h5>⏰ Library Timing</h5>
        <ul class="list-group list-group-flush mb-4">
          <li class="list-group-item">Opening: 9:00 AM</li>
          <li class="list-group-item">Closing: 5:00 PM</li>
          <li class="list-group-item text-danger">Saturday Off</li>
        </ul>

        <h5>✨ Our Facilities & Services</h5>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">📚 Fully Furnished Library</li>
          <li class="list-group-item">📶 Free Wi-Fi</li>
          <li class="list-group-item">📰 Newspapers</li>
          <li class="list-group-item">💬 Discussion Room</li>
          <li class="list-group-item">🚰 RO Water</li>
          <li class="list-group-item">🌿 Peaceful Study Environment</li>
        </ul>
      </div>
    </div>

    <!-- Admin Login Form -->
    <div class="col-md-8">
      <div id="main_content" class="shadow-sm card-hover">
        <h3 class="text-center mb-4">👤 Admin Login</h3>
        <form action="" method="post">
          <div class="mb-3">
            <label for="email" class="form-label">Email ID</label>
            <input type="text" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="text-center">
            <button type="submit" name="login" class="btn btn-primary px-4">Login</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

</body>
</html>
<?php 

if (isset($_POST['login'])) {

    $connection = mysqli_connect("localhost", "root", "", "lms");
    if (!$connection) {
        die("Database Connection Failed: " . mysqli_connect_error());
    }

    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement
    $stmt = mysqli_prepare(
        $connection,
        "SELECT * FROM admins WHERE email = ?"
    );
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        // ✅ Direct password comparison (NO HASHING)
        if ($password === $row['password']) {

            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['name']     = $row['name'];
            $_SESSION['email']    = $row['email'];

            header("Location: admin_dashboard.php");
            exit;

        } else {
            echo '<br><br><center>
                    <span class="alert alert-danger">
                        ❌ Wrong Password!
                    </span>
                  </center>';
        }

    } else {
        echo '<br><br><center>
                <span class="alert alert-danger">
                    ❌ Email not found!
                </span>
              </center>';
    }
  }
  ?>
		</div>
	</div>
</body>
</html>
