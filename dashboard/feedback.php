<?php
session_start();
include('../includes/db.php');

// Only Patients Can Access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../index.php");
    exit;
}

$patient_id = $_SESSION['user_id'];
$message = "";

// Handle Feedback Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $doctor_name = mysqli_real_escape_string($conn, $_POST['doctor_name']);
    $feedback_text = mysqli_real_escape_string($conn, $_POST['feedback_text']);

    $query = "INSERT INTO feedback (patient_id, appointment_id, doctor_name, feedback_text) 
              VALUES ('$patient_id', '$appointment_id', '$doctor_name', '$feedback_text')";

    if (mysqli_query($conn, $query)) {
        $message = "<div class='alert alert-success'>Feedback submitted successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch Appointments for Dropdown
$appointments = mysqli_query($conn, 
    "SELECT id, doctor_name, appointment_date 
     FROM appointments 
     WHERE patient_id='$patient_id' 
     ORDER BY appointment_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Provide Feedback - Care Compass Hospitals</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f0f8ff; padding: 30px; }
        .btn-primary { background-color: #0047ab; border: none; transition: 0.3s; }
        .btn-primary:hover { background-color: #003399; transform: translateY(-2px); }
        .modal-content { border-radius: 15px; }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-primary text-center"><i class="fas fa-comment-dots"></i> Provide Feedback</h2>
    <?php echo $message; ?>

    <!-- Feedback Button (Trigger Modal) -->
    <div class="text-center">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#feedbackModal">
            <i class="fas fa-comment-alt"></i> Give Feedback
        </button>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="feedbackModalLabel">Submit Feedback</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="appointment_id" class="form-label">Select Appointment</label>
            <select class="form-select" id="appointment_id" name="appointment_id" required>
              <option value="">Choose from your past appointments</option>
              <?php while ($row = mysqli_fetch_assoc($appointments)): ?>
                <option value="<?php echo $row['id']; ?>">
                  <?php echo "Dr. {$row['doctor_name']} - {$row['appointment_date']}"; ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="doctor_name" class="form-label">Doctor's Name</label>
            <input type="text" class="form-control" id="doctor_name" name="doctor_name" placeholder="Doctor's Name" required>
          </div>

          <div class="mb-3">
            <label for="feedback_text" class="form-label">Your Feedback</label>
            <textarea class="form-control" id="feedback_text" name="feedback_text" rows="4" placeholder="Write your feedback..." required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" name="submit_feedback" class="btn btn-primary">Submit Feedback</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
