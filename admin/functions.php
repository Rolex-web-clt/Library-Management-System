<?php
// Create a single database connection
function get_connection() {
    $connection = mysqli_connect("localhost", "root", "", "lms");
    if (!$connection) {
        die("Database Connection Failed: " . mysqli_connect_error());
    }
    return $connection;
}

// Generic function to get count from any table
 function get_count($table_name, $column_name='*' ){
    $connection = get_connection();
   // Use proper column for counting if provided, default '*'
    $query = "SELECT COUNT($column_name) as total_count FROM $table_name";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total_count'];
    } else {
        return 0; // fallback if query fails
    }
}

function get_issue_book_count() {
    // Count using primary key column 'issue_id'
    return get_count('issued_books', 'issue_id');
}

function get_book_count() {
    return get_count('books', 'book_id');
}

function get_user_count() {
    return get_count('users', 'id');
}

function get_author_count() {
    return get_count('authors', 'author_id');
}

function get_category_count() {
    return get_count('categories', 'cat_id');
}


function db_connect() {
    $conn = mysqli_connect("localhost", "root", "", "lms");
    if (!$conn) {
        die("Database connection failed");
    }
    return $conn;
}

/* Issued books (not returned) */
function get_user_issue_book_count() {
    if (!isset($_SESSION['id'])) return 0;

    $conn = db_connect();
    $user_id = $_SESSION['id'];

    $query = "
        SELECT COUNT(*) AS total
        FROM issued_books
        WHERE user_id = ?
        AND return_status = 'Issued'
    ";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}

/* Pending returns = issued & not returned */
function get_user_pending_return_count() {
    return get_user_issue_book_count();
}

/* Total books in library */
function get_total_books_count() {
    $conn = db_connect();
    $query = "SELECT COUNT(*) AS total FROM books";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}




// Function to request a book
function request_book($user_id, $book_id){
    $connection = db_connect();

    // Check if already requested
    $check_query = "
        SELECT * FROM book_requests 
        WHERE user_id='$user_id' 
        AND book_id='$book_id' 
        AND status='Pending'
    ";
    $check_run = mysqli_query($connection, $check_query);

    if(mysqli_num_rows($check_run) > 0){
        return "⚠️ You have already requested this book.";
    }

    // Insert request
    $request_date = date('Y-m-d');
    $insert_query = "
        INSERT INTO book_requests (user_id, book_id, request_date, status)
        VALUES ('$user_id','$book_id','$request_date','Pending')
    ";

    if(mysqli_query($connection, $insert_query)){
        return "✅ Book request submitted successfully!";
    } else {
        return "❌ Failed to request book.";
    }
}





?>
