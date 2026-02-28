<?php
session_start();
$connection = mysqli_connect("localhost","root","","lms");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Issue Book | LMS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">

<!-- TOP NAVBAR -->
<nav class="navbar navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="admin_dashboard.php">
        📚 Library Management System
    </a>
    <div class="text-white">
        Welcome, <strong><?php echo $_SESSION['name']; ?></strong>
    </div>
</nav>

<!-- SECOND NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <ul class="navbar-nav gap-2">
            <li class="nav-item">
                <a class="nav-link fw-semibold" href="admin_dashboard.php">Dashboard</a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle fw-semibold" href="#" data-bs-toggle="dropdown">Books</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="add_book.php">Add Book</a></li>
                    <li><a class="dropdown-item" href="manage_book.php">Manage Books</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle fw-semibold" href="#" data-bs-toggle="dropdown">Category</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="add_cat.php">Add Category</a></li>
                    <li><a class="dropdown-item" href="manage_cat.php">Manage Category</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle fw-semibold" href="#" data-bs-toggle="dropdown">Authors</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="add_author.php">Add Author</a></li>
                    <li><a class="dropdown-item" href="manage_author.php">Manage Authors</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link fw-semibold active" href="issue_book.php">Issue Book</a>
            </li>
        </ul>

        <a href="../logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">📖 Issue Book</h4>
                </div>

                <div class="card-body px-4 py-4">
                    <form method="post">

                        <div class="mb-3">
                            <label class="form-label">Book Name</label>
                            <input type="text" name="book_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Author Name</label>
                            <select name="book_author" class="form-select" required>
                                <option value="">Select Author</option>
                                <?php
                                $query = "SELECT author_name FROM authors";
                                $run = mysqli_query($connection,$query);
                                while($row = mysqli_fetch_assoc($run)){
                                    echo "<option>{$row['author_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Book Number</label>
                            <input type="text" name="book_no" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Student ID</label>
                            <input type="text" name="student_id" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Issue Date</label>
                            <input type="date" name="issue_date" value="<?php echo date('Y-m-d'); ?>" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="issue_book" class="btn btn-success btn-lg">
                                Issue Book
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
<?php
if (isset($_POST['issue_book'])) {

    $book_no    = $_POST['book_no'];
    $student_id = $_POST['student_id'];
    $issue_date = $_POST['issue_date'];

    // ===============================
    // CHECK STUDENT ID
    // ===============================
    $user_check = mysqli_query(
        $connection,
        "SELECT id FROM users WHERE id = '$student_id'"
    );

    if (mysqli_num_rows($user_check) == 0) {
        echo "<script>alert('Student ID is wrong');</script>";
        exit;
    }

    // ===============================
    // CHECK BOOK NUMBER
    // ===============================
    $book_check = mysqli_query(
        $connection,
        "SELECT book_id FROM books WHERE book_number = '$book_no'"
    );

    if (mysqli_num_rows($book_check) == 0) {
        echo "<script>alert('Book Number is wrong');</script>";
        exit;
    }

    $book = mysqli_fetch_assoc($book_check);
    $book_id = $book['book_id'];

    // ===============================
    // CHECK IF BOOK ALREADY ISSUED
    // ===============================
    $issue_check = mysqli_query(
        $connection,
        "SELECT * FROM issued_books 
         WHERE book_id = '$book_id' 
         AND return_status = 'Issued'"
    );

    if (mysqli_num_rows($issue_check) > 0) {
        echo "<script>alert('This book is already issued');</script>";
        exit;
    }

    // ===============================
    // ISSUE BOOK
    // ===============================
    $query = "INSERT INTO issued_books (book_id, user_id, issue_date)
              VALUES ('$book_id', '$student_id', '$issue_date')";

    if (mysqli_query($connection, $query)) {
        echo "<script>alert('Book Issued Successfully');</script>";
    } else {
        echo "<script>alert('Error issuing book');</script>";
    }
}
?>


?>
