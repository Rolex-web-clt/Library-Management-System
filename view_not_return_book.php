<?php
session_start();

/* DB Connection */
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed");
}

/* Correct Query */
$query = "
SELECT 
    books.book_name,
    authors.author_name,
    books.book_number,
    users.name AS student_name,
    issued_books.issue_date
FROM issued_books
JOIN books ON issued_books.book_id = books.book_id
JOIN authors ON books.author_id = authors.author_id
JOIN users ON issued_books.user_id = users.id
WHERE issued_books.return_status = 'Issued'
";

$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Not Returned Books</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .card-hover:hover { transform: translateY(-5px); transition: 0.3s ease; }
    .notice-bar {
      background-color: #fef3c7;
      color: #664d03;
      padding: 10px;
      border-radius: 5px;
      margin: 20px 0;
      font-weight: 500;
      text-align: center;
    }
  </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="user_dashboard.php">📚 LMS</a>
    <div class="ms-auto text-light">
      👋 Welcome: <strong><?php echo $_SESSION['name']; ?></strong>
    </div>
  </div>
</nav>

<div class="container">
  <div class="notice-bar">
    📢 Library opens at <strong>9:00 AM</strong> and closes at <strong>5:00 PM</strong>
  </div>
</div>

<div class="container mb-5">
  <div class="card shadow-lg card-hover p-4">
    <h4 class="text-center text-danger mb-4">📌 Not Returned Books</h4>

    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover text-center">
        <thead class="table-dark">
          <tr>
            <th>Book Name</th>
            <th>Author</th>
            <th>Book Number</th>
            <th>Student Name</th>
            <th>Issue Date</th>
          </tr>
        </thead>
        <tbody>

        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <tr>
                  <td>{$row['book_name']}</td>
                  <td>{$row['author_name']}</td>
                  <td>{$row['book_number']}</td>
                  <td>{$row['student_name']}</td>
                  <td>{$row['issue_date']}</td>
                </tr>";
            }
        } else {
            echo "
            <tr>
              <td colspan='5' class='text-danger fw-bold'>
                No pending book returns found
              </td>
            </tr>";
        }
        ?>

        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
