<?php
session_start();
include "admin/functions.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$conn = db_connect();
$user_id = $_SESSION['id'];

/* Fine rules */
$allowed_days = 7;
$fine_per_day = 10;

/* Fetch issued but not returned books */
$query = "
SELECT 
    books.book_name,
    issued_books.issue_date
FROM issued_books
JOIN books ON issued_books.book_id = books.book_id
WHERE issued_books.user_id = ?
AND issued_books.return_status = 'Issued'
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pending Fines</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .info-box {
      background: #fff3cd;
      border-left: 5px solid #ffc107;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="view_profile.php">📚 LMS</a>
  </div>
</nav>

<div class="container mt-4">

  <!-- Explanation -->
  <div class="info-box">
    <h5>📘 How Fine is Calculated?</h5>
    <ul class="mb-0">
      <li>Each book can be kept for <strong><?php echo $allowed_days; ?> days</strong></li>
      <li>After due date, fine is <strong>Rs<?php echo $fine_per_day; ?> per day</strong></li>
      <li>Fine = (Total days – <?php echo $allowed_days; ?>) × Rs<?php echo $fine_per_day; ?></li>
    </ul>
  </div>

  <!-- Fine Table -->
  <div class="card shadow-sm">
    <div class="card-header bg-danger text-white fw-bold">
      ⏳ Pending Fine Details
    </div>

    <div class="card-body table-responsive">
      <table class="table table-bordered text-center">
        <thead class="table-dark">
          <tr>
            <th>Book Name</th>
            <th>Issue Date</th>
            <th>Due Date</th>
            <th>Late Days</th>
            <th>Fine (Rs)</th>
          </tr>
        </thead>
        <tbody>

<?php
$total_fine = 0;
$today = new DateTime();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        $issue_date = new DateTime($row['issue_date']);
        $due_date = clone $issue_date;
        $due_date->modify("+$allowed_days days");

        $late_days = 0;
        $fine = 0;

        if ($today > $due_date) {
            $late_days = $due_date->diff($today)->days;
            $fine = $late_days * $fine_per_day;
        }

        $total_fine += $fine;

        echo "
        <tr>
          <td>{$row['book_name']}</td>
          <td>{$issue_date->format('Y-m-d')}</td>
          <td>{$due_date->format('Y-m-d')}</td>
          <td>{$late_days}</td>
          <td>Rs {$fine}</td>
        </tr>";
    }
} else {
    echo "
    <tr>
      <td colspan='5' class='text-success fw-bold'>
        No pending fines 🎉
      </td>
    </tr>";
}
?>

        </tbody>
      </table>
    </div>

    <div class="card-footer text-end fw-bold">
      Total Pending Fine: Rs <?php echo $total_fine; ?>
    </div>
  </div>

</div>

</body>
</html>
