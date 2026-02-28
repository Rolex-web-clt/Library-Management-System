<?php
$connection = mysqli_connect("localhost","root","","lms");
if (!$connection) die("Database connection failed!");

// Check if category ID is provided
if (!isset($_GET['cid'])) {
    header("Location: manage_cat.php");
    exit;
}

$cat_id = intval($_GET['cid']);

// Fetch category
$query = "SELECT * FROM categories WHERE cat_id = $cat_id";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Category not found'); window.location.href='manage_cat.php';</script>";
    exit;
}

$category = mysqli_fetch_assoc($result);

$error = "";
$success = "";

// Handle form submit
if (isset($_POST['update_cat'])) {
    $cat_name = trim(mysqli_real_escape_string($connection, $_POST['cat_name']));

    // Validation
    if ($cat_name === "") {
        $error = "Category name cannot be empty!";
    } elseif (!preg_match("/^[a-zA-Z0-9\s]+$/", $cat_name)) {
        $error = "Invalid category name! Only letters, numbers, and spaces allowed.";
    } else {
        // Check for duplicate
        $check_query = "SELECT * FROM categories WHERE cat_name='$cat_name' AND cat_id != $cat_id";
        $check_result = mysqli_query($connection, $check_query);
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Category with this name already exists!";
        } else {
            // Update category
            $update = "UPDATE categories SET cat_name='$cat_name' WHERE cat_id=$cat_id";
            if (mysqli_query($connection, $update)) {
                $success = "Category updated successfully!";
                echo "<script>alert('$success'); window.location.href='manage_cat.php';</script>";
                exit;
            } else {
                $error = "Update failed: ".mysqli_error($connection);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark text-center">
            Edit Category
        </div>
        <div class="card-body">

            <!-- Display server-side messages -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" id="editCategoryForm">
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="cat_name"
                           value="<?php echo htmlspecialchars($category['cat_name']); ?>"
                           class="form-control" required>
                </div>
                <button type="submit" name="update_cat" class="btn btn-success w-100">
                    Update Category
                </button>
            </form>

        </div>
    </div>
</div>

<!-- JS Validation -->
<script>
document.getElementById("editCategoryForm").addEventListener("submit", function(e){
    let catName = document.querySelector("input[name='cat_name']").value.trim();
    let pattern = /^[a-zA-Z0-9\s]+$/;

    if(catName === "") {
        alert("Category name cannot be empty!");
        e.preventDefault(); return;
    }
    if(!pattern.test(catName)) {
        alert("Invalid category name! Only letters, numbers, and spaces allowed.");
        e.preventDefault(); return;
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>