<?php
session_start();
include "admin/functions.php";
// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit;
}


// Database con
/* DEFINE VARIABLES (THIS FIXES ALL WARNINGS) */
$total_books      = get_total_books_count();
$issued_books     = get_user_issue_book_count();
$pending_returns  = get_user_pending_return_count();

/* OPTIONAL: If fines not implemented yet */
$pending_fines = 0;   // placeholdernection

$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get current user ID from session
$user_id = $_SESSION['id'];

// Prepared statement to fetch user data securely
$query = "SELECT name, email, mobile, address FROM users WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch user data
if ($user = mysqli_fetch_assoc($result)) {
    $name    = $user['name'];
    $email   = $user['email'];
    $mobile  = $user['mobile'];
    $address = $user['address'];
} else {
    // Optional: handle case if user not found
    $name = $email = $mobile = $address = "";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Library Dashboard</title>
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
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">

    <a class="navbar-brand fw-bold" href="user_dashboard.php">
      📚 LMS Dashboard
    </a>

    <div class="d-flex align-items-center text-light me-3">
      <span class="me-3">
        👋 Welcome, <strong><?php echo $_SESSION['name']; ?></strong>
      </span>
      <span class="badge bg-secondary">
        <?php echo $_SESSION['email']; ?>
      </span>
    </div>

    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
          My Profile
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
          <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
          <li><a class="dropdown-item"  href="change_password.php">Change Password</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- Notice Bar -->
<div class="alert alert-info text-center rounded-0 mb-4">
  📢 Library opens at <strong>9:00 AM</strong> and closes at <strong>5:00 PM</strong> (Saturday Closed)
</div>

<div class="container">

  <!-- Quick Stats Cards -->
  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card text-center shadow-sm card-hover">
        <div class="card-body">
          <h5 class="card-title"><a class="dropdown-item"  href="view_all_books.php">📚 Total Books</a></h5>
          <p class="card-text fs-4 fw-bold"><?php echo $total_books; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm card-hover">
        <div class="card-body">
          <h5 class="card-title"><a class="dropdown-item"  href="view_issued_book.php">📖 Issued Books</a></h5>
          <p class="card-text fs-4 fw-bold"><?php echo $issued_books; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm card-hover">
        <div class="card-body">
          <h5 class="card-title"><a class="dropdown-item"  href="view_not_return_book.php">⏳ Pending Returns</a></h5>
          <p class="card-text fs-4 fw-bold"><?php echo $pending_returns; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm card-hover">
        <div class="card-body">
          <h5 class="card-title"><a class="dropdown-item"  href="pending_fines.php">💰 Pending Fines</a></h5>
          <p class="card-text fs-4 fw-bold"><?php echo $pending_fines; ?> Rs</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Student Profile Card -->
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
          👤 Student Profile
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" value="<?php echo $name; ?>" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="text" class="form-control" value="<?php echo $email; ?>" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Mobile</label>
            <input type="text" class="form-control" value="<?php echo $mobile; ?>" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" value="<?php echo $address; ?>" disabled>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</body>
</html>

</body>
</html>
