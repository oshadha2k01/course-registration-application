<?php
// Start session only if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the student is logged in
if (isset($_SESSION['student_id'])) {
    include('../db/db.php');

    // Fetch the student data
    $student_id = $_SESSION['student_id'];
    $stmt = $conn->prepare("SELECT first_name FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $student_result = $stmt->get_result();

    // Check if student data was retrieved
    if ($student_result->num_rows > 0) {
        $student = $student_result->fetch_assoc();
    } else {
        $student = null; // Handle case when student data is not found
    }
}

// Handle logout logic
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login page
    exit();
}
?>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #007bff;">
    <div class="container-fluid">
        <div class="d-flex ms-auto">
            <!-- Home Icon -->
            <a href="home.php" class="navbar-brand text-white">
                <i class="bi bi-house-door"></i> Courses
            </a>
            <!-- Logout Icon with Logout Functionality -->
            <a href="?logout=true" class="navbar-brand text-white">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
        
        <!-- Conditionally Display Profile Icon if Student is Logged In -->
        <?php if (isset($student) && $student !== null): ?>
            <div class="d-flex">
                <!-- Profile Icon -->
                <a href="profile.php" class="navbar-text me-4 text-white text-decoration-none">
                    <i class="bi bi-person-circle"></i> Welcome! <?php echo htmlspecialchars($student['first_name']); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<!-- Bootstrap CSS and Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>