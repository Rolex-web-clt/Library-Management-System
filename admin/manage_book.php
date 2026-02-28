<?php
session_start();
$connection = mysqli_connect("localhost","root","","lms");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch books with author and category names
$query = "
    SELECT b.book_id, b.book_name, b.book_number, b.price,
           a.author_name, c.cat_name
    FROM books b
    JOIN authors a ON b.author_id = a.author_id
    JOIN categories c ON b.cat_id = c.cat_id
    ORDER BY b.book_id DESC
";
$books = mysqli_query($connection, $query);

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    // Check if book is issued
    $check = mysqli_query($connection, "
        SELECT * FROM issued_books 
        WHERE book_id = $delete_id AND return_status = 'Issued'
    ");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('❌ Book is currently issued. Cannot delete.');</script>";
        echo "<script>window.location='manage_book.php';</script>";
        exit;
    }

    // Safe to delete
    mysqli_query($connection, "DELETE FROM books WHERE book_id = $delete_id");
    header("Location: manage_book.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#f0f4f8;">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
        <div class="text-white ms-auto">
            Welcome: <strong><?php echo $_SESSION['name']; ?></strong> | 
            Email: <strong><?php echo $_SESSION['email']; ?></strong>
        </div>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <h3>Manage Books</h3>
        <a href="add_book.php" class="btn btn-success">Add New Book</a>
    </div>

    <input class="form-control mb-3" id="searchInput" type="text" placeholder="Search books...">

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Book Number</th>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookTable">
                <?php $i = 1; while($book = mysqli_fetch_assoc($books)): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $book['book_number']; ?></td>
                    <td><?php echo $book['book_name']; ?></td>
                    <td><?php echo $book['author_name']; ?></td>
                    <td><?php echo $book['cat_name']; ?></td>
                    <td><?php echo $book['price']; ?></td>
                    <td>
                        <!-- Pass book_id instead of book_number -->
                        <a href="edit_book.php?id=<?php echo $book['book_id']; ?>" class="btn btn-sm btn-primary">Edit</a>

                        <a href="manage_book.php?delete_id=<?php echo $book['book_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this book?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Search filter
const searchInput = document.getElementById("searchInput");
searchInput.addEventListener("keyup", function() {
    const filter = searchInput.value.toLowerCase();
    const rows = document.querySelectorAll("#bookTable tr");
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? "" : "none";
    });
});
</script>
</body>
</html> 