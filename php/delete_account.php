<?php
session_start();
include '../db/db.php';

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

try {
    // Begin transaction to ensure atomicity
    $conn->begin_transaction();

    // Delete enrollment records
    $query_delete_enrollments = "DELETE FROM enrollments WHERE student_id = ?";
    $stmt_delete_enrollments = $conn->prepare($query_delete_enrollments);
    $stmt_delete_enrollments->bind_param("i", $student_id);
    $stmt_delete_enrollments->execute();

    // Delete the student account
    $query_delete_student = "DELETE FROM students WHERE student_id = ?";
    $stmt_delete_student = $conn->prepare($query_delete_student);
    $stmt_delete_student->bind_param("i", $student_id);
    $stmt_delete_student->execute();

    // Commit transaction
    $conn->commit();

    // Destroy session
    session_destroy();

    // Redirect with success message
    echo "<script>
        alert('Your account has been deleted successfully.');
        setTimeout(function() {
            window.location.href = 'login.php';
        });
    </script>";
} catch (Exception $e) {
    // Rollback transaction in case of error
    $conn->rollback();

    // Redirect with error message
    echo "<script>
        alert('Something went wrong. Please try again.');
        setTimeout(function() {
            window.location.href = 'profile.php';
        }, 2000);
    </script>";
}
?>
<!-- Include Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
