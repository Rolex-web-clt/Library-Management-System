<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$connection = mysqli_connect("localhost","root","","lms");
if (!$connection) die("Database connection failed!");

// Check if id is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Error: Book ID not specified!";
    exit;
}

$book_id = (int)$_GET['id'];

// Fetch book
$result = mysqli_query($connection, "SELECT * FROM books WHERE book_id = $book_id");
if (mysqli_num_rows($result) == 0) {
    echo "Error: Book not found!";
    exit;
}

$book = mysqli_fetch_assoc($result);

// Fetch authors and categories
$authors = mysqli_query($connection, "SELECT * FROM authors");
$categories = mysqli_query($connection, "SELECT * FROM categories");

$error = "";
$success = "";

// Server-side validation & update
if (isset($_POST['update_book'])) {

    $book_name = trim(mysqli_real_escape_string($connection, $_POST['book_name']));
    $author_id = (int)$_POST['author_id'];
    $cat_id    = (int)$_POST['cat_id'];
    $price     = floatval($_POST['price']);

    // Validation
    if($book_name === "") {
        $error = "Book name cannot be empty!";
    } elseif(!preg_match("/^[a-zA-Z0-9\s]+$/", $book_name)) {
        $error = "Invalid book name! Only letters, numbers, and spaces allowed.";
    } elseif($author_id <= 0) {
        $error = "Please select a valid author.";
    } elseif($cat_id <= 0) {
        $error = "Please select a valid category.";
    } elseif($price <= 0) {
        $error = "Price must be a positive number.";
    } else {
        $update = "UPDATE books SET
                   book_name = '$book_name',
                   author_id = $author_id,
                   cat_id    = $cat_id,
                   price     = $price
                   WHERE book_id = $book_id";

        if(mysqli_query($connection, $update)) {
            $success = "Book updated successfully!";
            header("Location: manage_book.php");
            exit;
        } else {
            $error = "Database error: ".mysqli_error($connection);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card mx-auto shadow" style="max-width:600px;">
        <div class="card-header bg-warning text-center">
            ✏️ Edit Book
        </div>
        <div class="card-body">

            <!-- Server-side messages -->
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if(!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="post" id="editBookForm">

                <div class="mb-3">
                    <label>Book Number (Cannot change)</label>
                    <input type="text" class="form-control" value="<?php echo $book['book_number']; ?>" readonly>
                </div>

                <div class="mb-3">
                    <label>Book Name</label>
                    <input type="text" name="book_name" class="form-control"
                           value="<?php echo htmlspecialchars($book['book_name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Author</label>
                    <select name="author_id" class="form-select" required>
                        <option value="">-- Select Author --</option>
                        <?php
                        mysqli_data_seek($authors, 0); // reset pointer
                        while($author = mysqli_fetch_assoc($authors)) { ?>
                            <option value="<?php echo $author['author_id']; ?>"
                                <?php if($author['author_id'] == $book['author_id']) echo "selected"; ?>>
                                <?php echo $author['author_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Category</label>
                    <select name="cat_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <?php
                        mysqli_data_seek($categories, 0); // reset pointer
                        while($cat = mysqli_fetch_assoc($categories)) { ?>
                            <option value="<?php echo $cat['cat_id']; ?>"
                                <?php if($cat['cat_id'] == $book['cat_id']) echo "selected"; ?>>
                                <?php echo $cat['cat_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" class="form-control"
                           value="<?php echo $book['price']; ?>" required>
                </div>

                <button type="submit" name="update_book" class="btn btn-success w-100">Update Book</button>
            </form>
        </div>
    </div>
</div>

<!-- Client-side JS Validation -->
<script>
document.getElementById("editBookForm").addEventListener("submit", function(e){
    let bookName = document.querySelector("input[name='book_name']").value.trim();
    let authorId = document.querySelector("select[name='author_id']").value;
    let catId    = document.querySelector("select[name='cat_id']").value;
    let price    = parseFloat(document.querySelector("input[name='price']").value);

    let namePattern = /^[a-zA-Z0-9\s]+$/;

    if(bookName === "") {
        alert("Book name cannot be empty!");
        e.preventDefault(); return;
    }
    if(!namePattern.test(bookName)) {
        alert("Invalid book name! Only letters, numbers, and spaces allowed.");
        e.preventDefault(); return;
    }
    if(authorId === "" || parseInt(authorId) <= 0) {
        alert("Please select a valid author.");
        e.preventDefault(); return;
    }
    if(catId === "" || parseInt(catId) <= 0) {
        alert("Please select a valid category.");
        e.preventDefault(); return;
    }
    if(isNaN(price) || price <= 0) {
        alert("Price must be a positive number.");
        e.preventDefault(); return;
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>