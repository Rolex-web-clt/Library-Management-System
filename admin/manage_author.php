<?php
require("functions.php");
session_start();

$connection = mysqli_connect("localhost", "root", "", "lms");
$query = "SELECT * FROM authors";
$query_run = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Authors</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="card-header bg-dark text-white d-flex justify-content-between align-items-center px-4 py-3">
    <h4 class="mb-0">Manage Authors</h4>
     
            

                    <a class="btn btn-outline-light btn-sm px-3" href="add_author.php">Add Author</a>
            
        
    <a href="admin_dashboard.php" class="btn btn-outline-light btn-sm px-3">
        Back to Dashboard
    </a>
</div>

            <div class="card-body">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Author Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query_run)) { ?>
                            <tr>
                                <td><?php echo $row['author_id']; ?></td>
                                <td><?php echo $row['author_name']; ?></td>
                                <td>
                                    <a href="edit_author.php?id=<?php echo $row['author_id']; ?>"
                                        class="btn btn-sm btn-warning">Edit</a>

                              <a href="delete_author.php?id=<?php echo $row['author_id']; ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Are you sure you want to delete this author?');">
   Delete
</a>

                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</body>

</html>