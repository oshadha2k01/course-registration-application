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
    $stmt = $conn->prepare("SELECT first_name, role FROM students WHERE student_id = ?");
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

<!-- Horizontal Navbar -->
<nav class="navbar navbar-expand-lg navbar-light"
    style="background-color: #0d6efd; position: fixed; top: 0; left: 0; right: 0; z-index: 1000;">
    <div class="container-fluid">
        <div class="d-flex ms-auto">






            <!-- Logout Icon with Logout Functionality -->
            <a href="?logout=true" class="navbar-brand text-white">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>

        <!-- Conditionally Display Profile Icon if Student is Logged In -->
        <?php if (isset($student) && $student !== null): ?>
            <div class="d-flex">
                <!-- Profile Icon -->
                <a href="admin_profile.php"
                    class="navbar-text me-4 text-white text-decoration-none <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
                    <i class="bi bi-person-circle"></i> Welcome Admin
                    <?php echo htmlspecialchars($student['first_name']); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<!-- Left Vertical Navbar -->
<nav class="navbar navbar-expand-lg navbar-light position-fixed"
    style="top: 0; left: 0; bottom: 0; width: 200px; z-index: 999; background-color: #0d6efd;">
    <div class="container-fluid flex-column d-flex justify-content-between">

        <!-- Admin Dashboard Link (Visible for Admin) -->
        <?php if (isset($student) && $student['role'] === 'admin'): ?>
            <a href="admin_dashboard.php"
                class="navbar-brand text-white mb-3 d-flex align-items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php') ? 'active' : ''; ?>">
                <i class="bi bi-gear me-2"></i> <!-- me-2 adds margin to the right of the icon -->
                Dashboard
            </a>
        <?php endif; ?>

        <a href="admin_course_view.php"
            class="navbar-brand text-white mb-3 d-flex align-items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_course_view.php') ? 'active' : ''; ?>"
            style="margin-left: -30px;">
            <i class="bi bi-house-door me-2"></i> <!-- me-2 adds margin to the right of the icon -->
            Courses
        </a>


        <!-- Add Course Icon -->
        <a href="add_courses.php"
            class="navbar-brand text-white mb-3 d-flex align-items-center <?php echo (basename($_SERVER['PHP_SELF']) == 'add_courses.php') ? 'active' : ''; ?>">
            <i class="bi bi-plus-circle me-2"></i> <!-- me-2 adds margin to the right of the icon -->
            Add Course
        </a>

    </div>
</nav>



<!-- Bootstrap CSS and Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom CSS for Hover Effects and Active State -->
<style>
    .navbar-brand,
    .navbar-text {
        transition: all 0.3s ease;
    }

    .navbar-brand:hover,
    .navbar-text:hover {
        color: #007bff;
        /* Change the color when hovered */
    }

    .navbar-brand.active,
    .navbar-text.active {
        font-weight: bold;
        color: #007bff;
        /* Active menu color */
    }

    /* Optional: Add padding to the body to avoid content being hidden under the navbar */
    body {
        margin-left: 250px;
        /* Adjust this value based on navbar width */
    }
</style>