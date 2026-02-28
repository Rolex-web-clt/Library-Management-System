<?php
session_start();

$connection = mysqli_connect("localhost", "root", "", "lms");

if (!isset($_SESSION['id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['id'];

$query = "
    SELECT 
        b.book_name,
        a.author_name,
        b.book_number
    FROM issued_books ib
    JOIN books b ON ib.book_id = b.book_id
    JOIN authors a ON b.author_id = a.author_id
    WHERE ib.user_id = ?
    AND ib.return_status = 'Issued'
";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
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

    .notice-bar {
      background-color: #e9f7fe;
      color: #0c5460;
      padding: 10px;
      text-align: center;
      border-radius: 5px;
      margin: 20px 0;
      font-weight: 500;
    }

    .card-hover:hover {
      transform: translateY(-5px);
      transition: 0.3s ease;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="user_dashboard.php">📚 LMS</a>

    <div class="d-flex ms-auto text-light">
      <span class="navbar-text me-3">👋 Welcome: <strong><?php echo $_SESSION['name']; ?></strong></span>
      <span class="navbar-text">📧 Email: <strong><?php echo $_SESSION['email']; ?></strong></span>

      <ul class="navbar-nav ms-3">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">My Profile</a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
            <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
            <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
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
  <div class="card shadow-lg card-hover">
    <div class="card-header bg-primary text-white text-center fw-bold">
      📖 Issued Books Details
    </div>
    <div class="card-body p-4">

      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover text-center">
          <thead class="table-dark">
            <tr>
              <th>Name</th>
              <th>Author</th>
              <th>Number</th>
            </tr>
			</thead>
</tbody>
				<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo $row['book_name']; ?></td>
        <td><?php echo $row['author_name']; ?></td>
        <td><?php echo $row['book_number']; ?></td>
    </tr>
<?php } 
?>
				</table>
				</form>
			</div>
			<div class="col-md-2"></div>
		</div>
</body>
</html>
