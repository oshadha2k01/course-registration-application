<?php
include '../db/db.php'; // Include your database connection

// Initialize success message variable
$success_message = '';

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    // Delete the course from the database
    $query = "DELETE FROM courses WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $course_id);

    if ($stmt->execute()) {
        // Set success message
        $success_message = "Course deleted successfully!";
        // Redirect with success message as a URL parameter
        header("Location: admin_course_view.php?success=" . urlencode($success_message));
        exit; // Make sure to stop further script execution after redirect
    } else {
        echo "Error deleting course.";
    }
}
?>
