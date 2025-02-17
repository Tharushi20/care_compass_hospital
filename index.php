<?php
session_start();
 include('includes/db.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Care Compass Hospitals</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e6f0ff;
            color: #333;
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            background-color: #002366;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            color: #ffdd57 !important;
        }

        /* Carousel */
        .carousel-item img {
            height: 450px;
            object-fit: cover;
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            padding: 15px;
            border-radius: 5px;
        }

        /* Buttons */
        .btn-primary {
            background-color: #002366;
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #001a4d;
            transform: scale(1.05);
        }

        /* Services */
        .card {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        /* Contact Form */
        .form-control {
            border-radius: 8px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .form-control:focus {
            border-color: #002366;
            box-shadow: 0px 2px 10px rgba(0, 0, 36, 0.3);
        }

        /* Footer */
        footer {
            background-color: #002366;
            color: white;
            padding: 15px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#">Care Compass Hospitals</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
      <li class="nav-item">
  <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
</li>

<li class="nav-item">
  <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>
</li>
      </ul>
    </div>
  </div>
</nav>

<!-- Carousel Slider -->
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="assets/images/image1.jpg" class="d-block w-100" alt="Hospital Building">
      <div class="carousel-caption">
        <h3>Welcome to Care Compass Hospitals</h3>
        <p>Your health, our priority.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="assets/images/hospital2.jpg" class="d-block w-100" alt="Doctor Consulting">
      <div class="carousel-caption">
        <h3>Expert Doctors</h3>
        <p>Providing world-class healthcare services.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="assets/images/hospital3.jpg" class="d-block w-100" alt="Patient Care">
      <div class="carousel-caption">
        <h3>Advanced Facilities</h3>
        <p>Equipped with modern technology.</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
  </button>
</div>

<!-- Services Section -->
<div class="container my-5">
  <h2 class="text-center mb-4" style="color:#002366;">Our Services</h2>
  <div class="row">
    <div class="col-md-4">
      <div class="card shadow">
        <img src="assets/images/service1.jpg" class="card-img-top" alt="Service 1">
        <div class="card-body">
          <h5 class="card-title">24/7 Emergency</h5>
          <p class="card-text">Our emergency services are available round the clock to assist you.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow">
        <img src="assets/images/service2.jpg" class="card-img-top" alt="Service 2">
        <div class="card-body">
          <h5 class="card-title">Online Appointments</h5>
          <p class="card-text">Book your appointments easily through our online portal.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow">
        <img src="assets/images/service3.jpg" class="card-img-top" alt="Service 3">
        <div class="card-body">
          <h5 class="card-title">Advanced Labs</h5>
          <p class="card-text">Our laboratories are equipped with state-of-the-art technology.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Contact Section -->
<div class="container my-5">
  <h2 class="text-center" style="color:#002366;">Contact Us</h2>
  <form class="row g-3">
    <div class="col-md-6">
      <input type="text" class="form-control" placeholder="Your Name" required>
    </div>
    <div class="col-md-6">
      <input type="email" class="form-control" placeholder="Your Email" required>
    </div>
    <div class="col-12">
      <textarea class="form-control" placeholder="Your Message" rows="4" required></textarea>
    </div>
    <div class="col-12 text-center">
      <button type="submit" class="btn btn-primary">Send Message</button>
    </div>
  </form>
</div>
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#002366; color:white;">
        <h5 class="modal-title" id="loginModalLabel">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Show Error if Exists -->
        <?php 
          //session_start(); 
          if (isset($_SESSION['login_error'])): 
        ?>
          <div class="alert alert-danger">
            <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
          </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="login.php">
          <input type="hidden" name="login_submit" value="1">
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#0047ab; color:white;">
        <h5 class="modal-title" id="registerModalLabel">Register</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php 
          if (isset($_SESSION['register_error'])) {
              echo "<div class='alert alert-danger'>" . $_SESSION['register_error'] . "</div>";
              unset($_SESSION['register_error']);
          }
          if (isset($_SESSION['register_success'])) {
              echo "<div class='alert alert-success'>" . $_SESSION['register_success'] . "</div>";
              unset($_SESSION['register_success']);
          }
        ?>
        <form method="POST" action="register.php">
          <input type="hidden" name="register_submit" value="1">
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" id="email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" name="role" id="role" required>
              <option value="patient">Patient</option>
              <option value="staff">Staff</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- Footer -->
<footer>
  <p>&copy; 2025 Care Compass Hospitals | All Rights Reserved</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Auto-Open Register Modal if Error or Success -->
<script>
  <?php if (isset($_SESSION['register_error']) || isset($_SESSION['register_success'])): ?>
    var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
    registerModal.show();
  <?php endif; ?>
</script>


</body>
</html>
