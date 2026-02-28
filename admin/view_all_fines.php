<?php
session_start();
$connection = mysqli_connect("localhost","root","","lms");

$fine_per_day = 5;
$allowed_days = 7;

$query = "
SELECT u.name, b.book_name, ib.issue_date
FROM issued_books ib
JOIN users u ON ib.user_id = u.id
JOIN books b ON ib.book_id = b.book_id
WHERE ib.return_status = 'Issued'
";
$result = mysqli_query($connection,$query);
?>
<!DOCTYPE html>
<html>
<head>
<title>All Fines</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<h4 class="mb-3"><a class="navbar-brand" href="admin_dashboard.php">💰All Fines</a> </h4>
<table class="table table-bordered text-center">
<thead class="table-dark">
<tr>
<th>User</th>
<th>Book</th>
<th>Issue Date</th>
<th>Days Late</th>
<th>Fine (Rs)</th>
</tr>
</thead>
<tbody>

<?php
$today = date("Y-m-d");

while($row = mysqli_fetch_assoc($result)){
    $issue_date = $row['issue_date'];
    $days = (strtotime($today) - strtotime($issue_date)) / (60*60*24);
    $late_days = max(0, $days - $allowed_days);
    $fine = $late_days * $fine_per_day;
?>
<tr>
<td><?= $row['name']; ?></td>
<td><?= $row['book_name']; ?></td>
<td><?= $issue_date; ?></td>
<td><?= $late_days; ?></td>
<td><?= $fine; ?></td>
</tr>
<?php } ?>

</tbody>
</table>
</div>

</body>
</html>
