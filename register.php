<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("DB Connection Failed: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $mobile = trim($_POST['mobile']);
    $address = trim($_POST['address']);

    // 1️⃣ Empty validation
    if (empty($name) || empty($email) || empty($password) || empty($mobile) || empty($address)) {
        $errors[] = "All fields are required.";
    }

    // 2️⃣ Name validation
    if (!preg_match("/^[A-Za-z\s]{3,}$/", $name)) {
        $errors[] = "Name must contain only letters and minimum 3 characters.";
    }

    // 3️⃣ Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // 4️⃣ Password validation
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // 5️⃣ Mobile validation
    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "Mobile number must be 10 digits.";
    }

    // 6️⃣ Duplicate email check
    $stmt = $connection->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Email already registered.";
    }

    if (empty($errors)) {

        $stmt = $connection->prepare("INSERT INTO users(name,email,password,mobile,address) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $name, $email, $password, $mobile, $address);
        $stmt->execute();

        echo "<div class='alert alert-success text-center'>Registration Successful!</div>";
        header("refresh:2;url=index.php");
        exit();
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger text-center'>$error</div>";
        }
    }
}
?>