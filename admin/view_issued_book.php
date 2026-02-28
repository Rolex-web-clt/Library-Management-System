<?php
	session_start();
	#fetch data from database
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	$book_name = "";
	$author = "";
	$book_no = "";
	$student_name = "";
	$query = "select issued_books.book_name,issued_books.book_author,issued_books.book_no,users.name from issued_books left join users on issued_books.student_id = users.id where issued_books.status = 1";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Issued Books</title>
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
      <span class="navbar-text me-3">👋 Welcome: <strong><?php echo $_SESSION['name']; ?></strong></span>
      <span class="navbar-text">📧 Email: <strong><?php echo $_SESSION['email']; ?></strong></span>

      <ul class="navbar-nav ms-3">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">My Profile</a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">View Profile</a></li>
            <li><a class="dropdown-item" href="#">Edit Profile</a></li>
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

<!-- Issued Books Card -->
<div class="container mb-5">
  <div class="card shadow-lg card-hover p-4">
    <h4 class="text-center">📖 Issued Books Details</h4>

    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover text-center">
        <thead class="table-dark">
          <tr>
            <th>Book Name</th>
            <th>Author</th>
            <th>Book Number</th>
            <th>Student Name</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $query_run = mysqli_query($connection,$query);
            while ($row = mysqli_fetch_assoc($query_run)){
          ?>
            <tr>
              <td><?php echo $row['book_name'];?></td>
              <td><?php echo $row['book_author'];?></td>
              <td><?php echo $row['book_no'];?></td>
              <td><?php echo $row['name'];?></td>
            </tr>
          <?php
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


</body>
</html>
