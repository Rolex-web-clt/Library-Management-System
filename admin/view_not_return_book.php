<?php
session_start();
$connection = mysqli_connect("localhost","root","","lms");

$query = "
SELECT u.name, b.book_name, b.book_number, ib.issue_date
FROM issued_books ib
JOIN users u ON ib.user_id = u.id
JOIN books b ON ib.book_id = b.book_id
WHERE ib.return_status = 'Issued'
ORDER BY ib.issue_date ASC
";
$result = mysqli_query($connection,$query);
?>
<!DOCTYPE html>
<html>
<head>
<title>Not Returned Books</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<h4 class="mb-3"><a class="navbar-brand" href="admin_dashboard.php">❌ Not Returned Books</a></h4>
<table class="table table-bordered table-hover text-center">
<thead class="table-dark">
<tr>
<th>User</th>
<th>Book</th>
<th>Book No</th>
<th>Issue Date</th>
</tr>
</thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?= $row['name']; ?></td>
<td><?= $row['book_name']; ?></td>
<td><?= $row['book_number']; ?></td>
<td><?= $row['issue_date']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

</body>
</html>
