<!DOCTYPE html>
<html>
<head>
	<title>LMS</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
	
</head><style>
  body {
    background-color: #f8f9fa;
  }

  .sidebar-card, .content-card {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 8px;
  }

  .sidebar-card {
    height: 100%;
  }

  .section-title {
    font-weight: 600;
    color: #0d6efd;
    margin-bottom: 15px;
  }
</style>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      📚 Library Management System
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
      <ul class="navbar-nav gap-2">
        <li class="nav-item">
          <a class="btn btn-outline-light btn-sm px-3" href="admin/index.php">Admin Login</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light btn-sm px-3" href="signup.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light btn-sm px-3" href="index.php">Login</a>
        </li>

      </ul>
    </div>
  </div>
</nav>
<div class="alert alert-info text-center rounded-0 mb-4">
  📢 Library opens at <strong>9:00 AM</strong> and closes at <strong>5:00 PM</strong> (Saturday Closed)
</div>

<div class="container">
  <div class="row g-4">

    <!-- Sidebar -->
    <div class="col-md-4">
      <div class="sidebar-card shadow-sm">

        <h5 class="section-title">⏰ Library Timing</h5>
        <ul class="list-group list-group-flush mb-4">
          <li class="list-group-item">Opening: 9:00 AM</li>
          <li class="list-group-item">Closing: 5:00 PM</li>
          <li class="list-group-item text-danger">Saturday Closed</li>
        </ul>

        <h5 class="section-title">✨ Our Facilities & Services</h5>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">📚 Fully Furnished Library</li>
          <li class="list-group-item">📶 Free Wi-Fi</li>
          <li class="list-group-item">📰 Newspapers</li>
          <li class="list-group-item">💬 Discussion Room</li>
          <li class="list-group-item">🚰 RO Drinking Water</li>
          <li class="list-group-item">🌿 Peaceful Study Environment</li>
        </ul>

      </div>
    </div>

    <!-- Registration Form -->
    <div class="col-md-8">
      <div class="content-card shadow-lg">

        <h3 class="text-center fw-bold mb-4">
          👤 User Registration
        </h3>
<form action="register.php" method="POST" onsubmit="return validateForm()">

  <div class="mb-3">
    <label class="form-label">Full Name</label>
    <input type="text" name="name" id="name"
           class="form-control"
           pattern="[A-Za-z\s]{3,}"
           title="Name must contain only letters and at least 3 characters"
           required>
  </div>

  <div class="mb-3">
    <label class="form-label">Email ID</label>
    <input type="email" name="email" id="email"
           class="form-control"
           required>
  </div>

  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" id="password"
           class="form-control"
           minlength="6"
           required>
  </div>

  <div class="mb-3">
    <label class="form-label">Mobile Number</label>
    <input type="text" name="mobile" id="mobile"
           class="form-control"
           pattern="[0-9]{10}"
           title="Enter valid 10 digit mobile number"
           required>
  </div>

  <div class="mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" id="address"
              class="form-control"
              rows="3"
              minlength="5"
              required></textarea>
  </div>

  <button type="submit" class="btn btn-primary px-4">
    Register
  </button>

</form>

      </div>
    </div>

  </div>
</div>

<script>
function validateForm() {

  let password = document.getElementById("password").value;
  let mobile = document.getElementById("mobile").value;

  if (password.length < 6) {
      alert("Password must be at least 6 characters");
      return false;
  }

  if (!/^[0-9]{10}$/.test(mobile)) {
      alert("Mobile number must be 10 digits");
      return false;
  }

  return true;
}
</script>
</body>
</html>