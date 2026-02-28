<?php
session_start();
require 'functions.php';

// Redirect if admin not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Fetch counts
$author_count = get_author_count();
$user_count = get_user_count();
$book_count = get_book_count();
$issue_book_count = get_issue_book_count();
$category_count = get_category_count();

// Fetch Top 5 Users (by issued books)
$connection = mysqli_connect("localhost","root","","lms");
$top_users = [];
$user_query = "SELECT u.name, COUNT(ib.issue_id) as issued_count 
               FROM users u
               JOIN issued_books ib ON u.id = ib.user_id
               GROUP BY u.id ORDER BY issued_count DESC LIMIT 5";
$res = mysqli_query($connection, $user_query);
while($row = mysqli_fetch_assoc($res)){
    $top_users[] = $row;
}

// Fetch Top 5 Authors (by book count)
$top_authors = [];
$author_query = "SELECT a.author_name, COUNT(b.book_id) as book_count
                 FROM authors a
                 JOIN books b ON a.author_id = b.author_id
                 GROUP BY a.author_id ORDER BY book_count DESC LIMIT 5";
$res2 = mysqli_query($connection, $author_query);
while($row = mysqli_fetch_assoc($res2)){
    $top_authors[] = $row;
}

// Calculate available books for Pie Chart
$available_books = $book_count - $issue_book_count;

// ===== NEW: Fine Calculation =====
$fine_per_day = 5;
$allowed_days = 7;
$total_fine = 0;

$fine_query = "SELECT issue_date 
               FROM issued_books 
               WHERE return_status = 'Issued'";
$fine_res = mysqli_query($connection, $fine_query);
$today = date("Y-m-d");
while ($row = mysqli_fetch_assoc($fine_res)) {
    $days = (strtotime($today) - strtotime($row['issue_date'])) / (60*60*24);
    $late_days = max(0, $days - $allowed_days);
    $total_fine += ($late_days * $fine_per_day);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | LMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
.card-hover:hover { transform: translateY(-5px); transition: all 0.3s ease; box-shadow: 0 8px 20px rgba(0,0,0,0.2); }
.card-title { font-weight: 600; display:flex; align-items:center; justify-content:center; }
.trend-up { color: #198754; margin-left: 5px; }
.trend-down { color: #dc3545; margin-left: 5px; }
.navbar .navbar-text { font-weight: 500; }
.footer { background-color: #343a40; color: #fff; padding: 15px 0; text-align: center; margin-top: 50px; }
.dark-mode { background-color: #121212; color: #f1f1f1; }
.dark-mode .card { background-color: #1e1e1e; }
</style>
</head>
<body id="body">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
<div class="container-fluid">
<a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-book"></i> LMS</a>
<div class="d-flex align-items-center ms-auto text-white me-3">
<span class="me-3"><i class="bi bi-person-circle"></i> Welcome: <strong><?php echo $_SESSION['name']; ?></strong></span>
<span><i class="bi bi-envelope"></i> Email: <strong><?php echo $_SESSION['email']; ?></strong></span>
</div>
<ul class="navbar-nav ms-auto">
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="bi bi-gear"></i> My Profile</a>
<ul class="dropdown-menu dropdown-menu-end">
<li><a class="dropdown-item" href="view_profile.php">View Profile</a></li>
<li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
<li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
</ul>
</li>
<li class="nav-item">
<a class="nav-link" href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</li>
<li class="nav-item ms-3">
<button id="darkModeToggle" class="btn btn-outline-light btn-sm"><i class="bi bi-moon-fill"></i> Dark Mode</button>
</li>
</ul>
</div>
</nav>

<div class="container mt-5">
<div class="row g-4 text-center">
    <!-- Dashboard Cards with Trend -->
    <?php

    $linkMap = [
    'Authors' => 'manage_author.php',
    'Users' => 'manage_user.php',
    'Books' => 'manage_book.php',
    'Issued Books' => 'issue_book.php',
    'Categories' => 'manage_cat.php'
];
    $cards = [
        ['title'=>'Authors','count'=>$author_count,'icon'=>'bi-pen-fill','bg'=>'bg-primary','trend'=>'+2'],
        ['title'=>'Users','count'=>$user_count,'icon'=>'bi-people-fill','bg'=>'bg-success','trend'=>'+3'],
        ['title'=>'Books','count'=>$book_count,'icon'=>'bi-journal-bookmark-fill','bg'=>'bg-warning','trend'=>'-1'],
        ['title'=>'Issued Books','count'=>$issue_book_count,'icon'=>'bi-box-seam','bg'=>'bg-danger','trend'=>'+4'],
        ['title'=>'Categories','count'=>$category_count,'icon'=>'bi-tags-fill','bg'=>'bg-info','trend'=>'+1']
    ];
foreach ($cards as $card):
    $link = isset($linkMap[$card['title']]) ? $linkMap[$card['title']] : '#';
?>


<div class="col-md-4 col-lg-2">
    <a href="<?php echo $link; ?>" style="text-decoration:none; color:inherit;">
        <div class="card text-white <?php echo $card['bg']; ?> card-hover shadow-sm">
            <div class="card-body">
                <i class="bi <?php echo $card['icon']; ?> display-4 mb-2"></i>
                <h5 class="card-title">
                    <?php echo $card['title']; ?>
                    <?php if($card['trend'][0] == '+'): ?>
                        <i class="bi bi-arrow-up trend-up"></i>
                    <?php else: ?>
                        <i class="bi bi-arrow-down trend-down"></i>
                    <?php endif; ?>
                </h5>
                <p class="card-text display-4 counter" data-target="<?php echo $card['count']; ?>">0</p>
            </div>
        </div>
    </a>
</div>
<?php endforeach; ?>

<!-- NEW: Quick Links for Not Returned Books & View Fines -->


<div class="col-12 mt-4 text-center">
    <a href="view_not_return_book.php" class="btn btn-danger me-3">
        📕 Not Returned Books
    </a>
    <a href="view_all_fines.php" class="btn btn-warning">
        💰 View All Fines
    </a>
</div>

    <!-- Progress Bar -->
    <div class="col-12 mt-4">
        <h5 class="mb-2">Issued Books Percentage</h5>
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: <?php echo ($book_count > 0 ? ($issue_book_count/$book_count)*100 : 0); ?>%;" aria-valuenow="<?php echo $issue_book_count; ?>" aria-valuemin="0" aria-valuemax="<?php echo $book_count; ?>"><?php echo round(($book_count > 0 ? ($issue_book_count/$book_count)*100 : 0)); ?>%</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="col-md-6 mt-5">
        <canvas id="pieChart"></canvas>
    </div>
    <div class="col-md-6 mt-5">
        <canvas id="barChart"></canvas>
    </div>

    <!-- NEW: Fine Chart -->
    <div class="col-md-6 mt-5">
        <canvas id="fineChart"></canvas>
    </div>

    <!-- Top 5 Users -->
    <div class="col-md-6 mt-5">
        <h5>Top 5 Users (Most Issued Books)</h5>
        <ul class="list-group">
            <?php foreach($top_users as $user): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo $user['name']; ?>
                <span class="badge bg-primary rounded-pill"><?php echo $user['issued_count']; ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Top 5 Authors -->
    <div class="col-md-6 mt-5">
        <h5>Top 5 Authors (Most Books)</h5>
        <ul class="list-group">
            <?php foreach($top_authors as $author): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo $author['author_name']; ?>
                <span class="badge bg-success rounded-pill"><?php echo $author['book_count']; ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>
</div>

<footer class="footer">
    &copy; <?php echo date("Y"); ?> Library Management System. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Counter Animation
const counters = document.querySelectorAll('.counter');
counters.forEach(counter => {
    const updateCount = () => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;
        const speed = 50;
        const increment = target / speed;
        if(count < target){
            counter.innerText = Math.ceil(count + increment);
            setTimeout(updateCount, 20);
        } else { counter.innerText = target; }
    };
    updateCount();
});

// Pie Chart (Issued vs Available)
const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: ['Issued Books','Available Books'],
        datasets:[{
            data: [<?php echo $issue_book_count; ?>, <?php echo $available_books; ?>],
            backgroundColor: ['rgba(220,53,69,0.7)','rgba(13,202,240,0.7)'],
            borderColor:['rgba(220,53,69,1)','rgba(13,202,240,1)'],
            borderWidth:1
        }]
    },
    options: { responsive:true, plugins:{ legend:{position:'bottom'} } }
});

// Bar Chart (Top Categories)
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx,{
    type:'bar',
    data:{
        labels: ['Authors','Users','Books','Issued Books','Categories'],
        datasets:[{
            label:'Count',
            data: [<?php echo "$author_count,$user_count,$book_count,$issue_book_count,$category_count"; ?>],
            backgroundColor:[
                'rgba(13,110,253,0.7)',
                'rgba(25,135,84,0.7)',
                'rgba(255,193,7,0.7)',
                'rgba(220,53,69,0.7)',
                'rgba(13,202,240,0.7)'
            ]
        }]
    },
    options:{ responsive:true, scales:{y:{beginAtZero:true}} }
});

// NEW: Fine Chart
const fineCtx = document.getElementById('fineChart').getContext('2d');
new Chart(fineCtx, {
    type: 'doughnut',
    data: {
        labels: ['Total Pending Fine'],
        datasets: [{
            data: [<?php echo $total_fine; ?>],
            backgroundColor: ['rgba(255,193,7,0.7)'],
            borderColor: ['rgba(255,193,7,1)'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: '💰 Total Pending Fine (Rs)'
            },
            legend: { display: false }
        }
    }
});

// Dark Mode Toggle
const darkToggle = document.getElementById('darkModeToggle');
const body = document.getElementById('body');
darkToggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    darkToggle.innerHTML = body.classList.contains('dark-mode') ? '<i class="bi bi-sun-fill"></i> Light Mode' : '<i class="bi bi-moon-fill"></i> Dark Mode';
});
</script>
</body>
</html> 