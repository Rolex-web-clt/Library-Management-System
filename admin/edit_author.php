<?php
$connection = mysqli_connect("localhost","root","","lms");
if (!$connection) die("Database connection failed");

$id = (int)$_GET['id'];

// Fetch author info
$query = "SELECT * FROM authors WHERE author_id=$id";
$query_run = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($query_run);

$error = "";
$success = "";

// Server-side validation & update
if(isset($_POST['update'])) {
    $name = trim(mysqli_real_escape_string($connection, $_POST['author_name']));

    if($name === "") {
        $error = "Author name cannot be empty!";
    } elseif(!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error = "Invalid name! Only letters and spaces allowed.";
    } else {
        // Check duplicate
        $check_query = "SELECT * FROM authors WHERE author_name='$name' AND author_id != $id";
        $check_result = mysqli_query($connection, $check_query);
        if(mysqli_num_rows($check_result) > 0) {
            $error = "Author with this name already exists!";
        } else {
            $update_query = "UPDATE authors SET author_name='$name' WHERE author_id=$id";
            if(mysqli_query($connection, $update_query)) {
                $success = "Author updated successfully!";
                // Optionally redirect after update
                header("Location: manage_author.php");
                exit;
            } else {
                $error = "Database error: " . mysqli_error($connection);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Author</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Edit Author</h4>
        </div>

        <div class="card-body">

            <!-- Server-side messages -->
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if(!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="post" id="editAuthorForm">
                <div class="mb-3">
                    <label>Author Name</label>
                    <input type="text" name="author_name" class="form-control"
                           value="<?php echo htmlspecialchars($row['author_name']); ?>" required>
                </div>

                <button type="submit" name="update" class="btn btn-success w-100">Update</button>
                <a href="manage_author.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>

<!-- JS Validation -->
<script>
document.getElementById("editAuthorForm").addEventListener("submit", function(e){
    let name = document.querySelector("input[name='author_name']").value.trim();
    let pattern = /^[a-zA-Z\s]+$/;

    if(name === "") {
        alert("Author name cannot be empty!");
        e.preventDefault();
        return;
    }

    if(!pattern.test(name)) {
        alert("Invalid name! Only letters and spaces are allowed.");
        e.preventDefault();
        return;
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>