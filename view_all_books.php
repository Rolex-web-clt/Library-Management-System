<?php
session_start();
require 'admin/functions.php';
/* DB Connection */
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed");
}

/* Fetch all books from library */
$query = "
SELECT 
    books.book_id,
    books.book_name,
    authors.author_name,
    categories.cat_name,
    books.book_number,
    books.price
FROM books
JOIN authors ON books.author_id = authors.author_id
JOIN categories ON books.cat_id = categories.cat_id
ORDER BY books.book_name ASC
";

$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Books - Library</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .card-hover:hover { transform: translateY(-5px); transition: 0.3s ease; }
  </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="user_dashboard.php">📚 LMS</a>
    <span class="navbar-text text-light ms-auto">
      👋 Welcome, <strong><?php echo $_SESSION['name']; ?></strong>
    </span>
  </div>
</nav>

<!-- Main Content -->
<div class="container my-4">
  <div class="card shadow-lg card-hover p-4">
    <h4 class="text-center mb-4">📖 All Books Available in Library</h4>

    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover text-center">
        <thead class="table-dark">
          <tr>
            <th>Sno</th>
            <th>Book Name</th>
            <th>Author</th>
            <th>Category</th>
            <th>Book Number</th>
            <th>Price</th>
           
          </tr>
        </thead>
        <tbody>

        
<?php
if (mysqli_num_rows($result) > 0) {
    $count = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "
        <tr>
          <td>{$count}</td>
          <td>{$row['book_name']}</td>
          <td>{$row['author_name']}</td>
          <td>{$row['cat_name']}</td>
          <td>{$row['book_number']}</td>
          <td>{$row['price']}</td>
         
        </tr>";
        $count++;
    }
} 
?>


        </tbody>
      </table>
    </div>

    <a href="user_dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
  </div>
</div>

</body>
</html>
