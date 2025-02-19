<?php
session_start();
include('../includes/db.php');

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../index.php");
    exit;
}
// echo "Your Session User ID: " . $_SESSION['user_id'];
$patient_id = $_SESSION['user_id'];
$feedbackMessage = "";
// Fetch user email from the users table
$user_query = mysqli_query($conn, "SELECT email FROM users WHERE id='$patient_id'");
$user_data = mysqli_fetch_assoc($user_query);
$email = $user_data ? $user_data['email'] : 'Patient';

// Extract the part before '@'
$display_name = explode('@', $email)[0];


// Handle Feedback Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $doctor_name = mysqli_real_escape_string($conn, $_POST['doctor_name']);
    $feedback_text = mysqli_real_escape_string($conn, $_POST['feedback_text']);

    $query = "INSERT INTO feedback (patient_id, appointment_id, doctor_name, feedback_text, created_at) 
              VALUES ('$patient_id', '$appointment_id', '$doctor_name', '$feedback_text', NOW())";
    
    if (mysqli_query($conn, $query)) {
        $feedbackMessage = "<div class='alert alert-success'>Thank you! Your feedback has been submitted.</div>";
    } else {
        $feedbackMessage = "<div class='alert alert-danger'>Error submitting feedback.</div>";
    }
}

// Fetch Appointments for Dropdown Selection
$appointments = mysqli_query($conn, 
    "SELECT id, doctor_name, appointment_date FROM appointments 
     WHERE patient_id='$patient_id' ORDER BY appointment_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard - Care Compass Hospitals</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8ff;
        }

        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            background-color: #0047ab;
            color: white;
            padding-top: 20px;
        }

        .sidebar h4 {
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            padding: 12px 20px;
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: flex;
            align-items: center;
            transition: background 0.3s;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover {
            background-color: #0056d2;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        
        .header {
            padding: 20px;
            background: linear-gradient(to right, #0047ab, #0056d2);
            color: white;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header h2 {
            font-weight: bold;
            font-size: 28px;
        }
        .header p {
            font-size: 16px;
            margin-top: 5px;
            opacity: 0.9;
        }


        /* Dashboard Cards */
        .dashboard-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 3px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
        }

        .dashboard-card i {
            font-size: 40px;
            color: #0056d2;
        }

        .dashboard-card h5 {
            font-weight: bold;
            margin: 10px 0;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        /* Logout Button */
        .logout-btn {
            position: absolute;
            bottom: 30px;
            left: 20px;
            width: 80%;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Patient Panel</h4>
    <a href="patient.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="appointments.php"><i class="fas fa-calendar-check"></i> My Appointments</a>
    <a href="book_appointment.php"><i class="fas fa-calendar-check"></i> Book Appointments</a>
    <a href="medical_records_patient.php"><i class="fas fa-notes-medical"></i> Medical Records</a>
    <a href="lab_reports_patient.php"><i class="fas fa-flask"></i> Lab Reports</a>
    <a href="#" data-bs-toggle="modal" data-bs-target="#feedbackModal"><i class="fas fa-comment-dots"></i> Provide Feedback</a>
    <a href="../logout.php" class="btn btn-danger logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2>Welcome, <?php echo htmlspecialchars($display_name); ?> 👋</h2>
        <p>View your health details and manage appointments.</p>
    </div>

    <!-- Dashboard Cards -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="dashboard-card">
            <i class="fas fa-calendar-check"></i>
            <h5>Upcoming Appointments</h5>
            <p>
                <?php
                $today = date('Y-m-d');
                $appointment_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointments WHERE patient_id='$patient_id' AND appointment_date >= '$today'"))['count'];
                echo $appointment_count;
                ?>
            </p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dashboard-card">
            <i class="fas fa-notes-medical"></i>
            <h5>Medical Records</h5>
            <p>
                <?php
                $medical_records_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM medical_records WHERE patient_id='$patient_id'"))['count'];
                echo $medical_records_count;
                ?>
            </p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dashboard-card">
            <i class="fas fa-comment-dots"></i>
            <h5>Feedback Submitted</h5>
            <p>
                <?php
                $feedback_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM feedback WHERE patient_id='$patient_id'"))['count'];
                echo $feedback_count;
                ?>
            </p>
        </div>
    </div>
</div>

</div>
<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Provide Feedback</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <?php echo $feedbackMessage; ?>
          <div class="mb-3">
            <label for="appointment_id" class="form-label">Select Appointment</label>
            <select name="appointment_id" class="form-select" required>
              <option value="">Select an appointment</option>
              <?php while ($row = mysqli_fetch_assoc($appointments)): ?>
                <option value="<?php echo $row['id']; ?>">
                  <?php echo "Dr. " . $row['doctor_name'] . " - " . $row['appointment_date']; ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="doctor_name" class="form-label">Doctor's Name</label>
            <input type="text" class="form-control" name="doctor_name" required>
          </div>
          <div class="mb-3">
            <label for="feedback_text" class="form-label">Your Feedback</label>
            <textarea name="feedback_text" class="form-control" placeholder="Write your feedback here..." rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="submit_feedback" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


</body>
</html>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


