<?php
$connection = mysqli_connect("localhost","root","","lms");

$cat_id = intval($_GET['cid']);

// CHECK IF CATEGORY IS USED
$check = mysqli_query(
    $connection,
    "SELECT * FROM books WHERE cat_id = $cat_id"
);

if (mysqli_num_rows($check) > 0) {
    echo "<script>
        alert('Cannot delete category. Books are assigned to this category.');
        window.location.href='manage_cat.php';
    </script>";
    exit;
}

// DELETE CATEGORY
$query = "DELETE FROM categories WHERE cat_id = $cat_id";
mysqli_query($connection, $query);

echo "<script>
    alert('Category deleted successfully');
    window.location.href='manage_cat.php';
</script>";
?>
