<?php
session_start();
include "admin/functions.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .card-hover:hover { transform: translateY(-5px); transition: 0.3s ease; }
    .notice-bar {
      background-color: #e9f7fe;
      color: #0c5460;
      padding: 10px;
      border-radius: 5px;
      margin: 20px 0;
      font-weight: 500;
      text-align: center;
    }
  </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="view_profile.php">📚 LMS</a>
    <span class="navbar-text text-light ms-auto">
      👋 Welcome, <strong><?php echo $_SESSION['name']; ?></strong>
    </span>
  </div>
</nav>

<!-- Notice -->
<div class="container">
  <div class="notice-bar">
    📢 Library opens at <strong>9:00 AM</strong> and closes at <strong>5:00 PM</strong>
  </div>
</div>

<!-- Dashboard Cards -->
<div class="container">
  <div class="row g-4">

    <!-- Issued Books -->
    <div class="col-md-4">
      <div class="card shadow-sm card-hover">
        <div class="card-header bg-primary text-white fw-bold">Book Issued</div>
        <div class="card-body">
          <p class="fs-5">
            No of books issued:
            <strong><?php echo get_user_issue_book_count(); ?></strong>
          </p>
          <a href="view_issued_book.php" class="btn btn-success">View Issued Books</a>
        </div>
      </div>
    </div>

    <!-- Pending Returns -->
    <div class="col-md-4">
      <div class="card shadow-sm card-hover">
        <div class="card-header bg-info text-white fw-bold">Pending Returns</div>
        <div class="card-body">
          <p class="fs-5">
            No of pending returns:
            <strong><?php echo get_user_pending_return_count(); ?></strong>
          </p>
          <a href="view_not_return_book.php" class="btn btn-info text-white">View Details</a>
        </div>
      </div>
    </div>

    <!-- Total Books -->
    <div class="col-md-4">
      <div class="card shadow-sm card-hover">
        <div class="card-header bg-warning text-dark fw-bold">Total Books</div>
        <div class="card-body">
          <p class="fs-5">
            Total books in library:
            <strong><?php echo get_total_books_count(); ?></strong>
          </p>
          <a href="view_all_books.php" class="btn btn-warning text-dark">View Books</a>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
