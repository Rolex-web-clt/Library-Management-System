<?php
	require("functions.php");
	session_start();
	#fetch data from database
	$connection = mysqli_connect("localhost","root","","lms");
	$name = "";
	$email = "";
	$mobile = "";
	$query = "select * from admins where email = '$_SESSION[email]'";
	$query_run = mysqli_query($connection,$query);
	while ($row = mysqli_fetch_assoc($query_run)){
		$name = $row['name'];
		$email = $row['email'];
		$mobile = $row['mobile'];
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Category</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
	<style>
		body {
			background-color: #f8f9fa;
		}
		.navbar-brand {
			font-weight: bold;
		}
		.navbar-nav .nav-link {
			padding: 0.5rem 1rem;
		}
		.card-header {
			background: linear-gradient(90deg, #0d6efd, #6610f2);
			color: white;
			font-weight: bold;
			font-size: 1.2rem;
			text-align: center;
		}
		.marquee-wrapper {
			background-color: #e3f2fd;
			padding: 5px;
			border-radius: 5px;
			margin-bottom: 20px;
		}
		.btn-custom {
			margin-right: 5px;
		}
	</style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
	<div class="container-fluid">
		<a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
		<div class="d-flex ms-auto text-white align-items-center">
			<span class="me-3">Welcome: <strong><?php echo $_SESSION['name'];?></strong></span>
			<span>Email: <strong><?php echo $_SESSION['email'];?></strong></span>
		</div>
	</div>
</nav>

<!-- Secondary Navbar -->
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
	<div class="container-fluid">
		<ul class="navbar-nav mx-auto">
			<li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">Books</a>
				<ul class="dropdown-menu">
					<li><a class="dropdown-item" href="add_book.php">Add New Book</a></li>
					<li><a class="dropdown-item" href="manage_book.php">Manage Books</a></li>
				</ul>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">Category</a>
				<ul class="dropdown-menu">
					<li><a class="dropdown-item" href="add_cat.php">Add New Category</a></li>
					<li><a class="dropdown-item" href="manage_cat.php">Manage Category</a></li>
				</ul>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">Authors</a>
				<ul class="dropdown-menu">
					<li><a class="dropdown-item" href="add_author.php">Add New Author</a></li>
					<li><a class="dropdown-item" href="manage_author.php">Manage Author</a></li>
				</ul>
			</li>
			<li class="nav-item"><a class="nav-link" href="issue_book.php">Issue Book</a></li>
		</ul>
	</div>
</nav>

<!-- Marquee -->
<div class="container marquee-wrapper">
	
</div>

<!-- Main Content -->
<div class="container">
	<div class="card shadow mb-4">
		<div class="card-header">Manage Category</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped text-center align-middle">
					<thead class="table-dark">
						<tr>
							<th>Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
				<?php
$query = "SELECT * FROM categories";
$query_run = mysqli_query($connection, $query);

if (mysqli_num_rows($query_run) > 0) {
    while ($row = mysqli_fetch_assoc($query_run)) {
?>
<tr>
    <td><?php echo $row['cat_name']; ?></td>
    <td>
        <a href="edit_cat.php?cid=<?php echo $row['cat_id']; ?>" 
           class="btn btn-sm btn-warning btn-custom">Edit</a>

        <a href="delete_cat.php?cid=<?php echo $row['cat_id']; ?>" 
           class="btn btn-sm btn-danger btn-custom"
           onclick="return confirm('Are you sure to delete this category?');">
           Delete
        </a>
    </td>
</tr>
<?php
    }
} else {
    echo "<tr><td colspan='2'>No categories found</td></tr>";
}
?>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

</body>
</html>
