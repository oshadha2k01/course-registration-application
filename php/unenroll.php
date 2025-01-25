<?php
session_start();
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_SESSION['student_id'];
    $course_id = $_POST['course_id'];

    // Check if the student is enrolled in the course
    $stmt_check_enrollment = $conn->prepare("SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?");
    $stmt_check_enrollment->bind_param("ii", $student_id, $course_id);
    $stmt_check_enrollment->execute();
    $result_enrollment = $stmt_check_enrollment->get_result();

    if ($result_enrollment->num_rows === 0) {
        $_SESSION['error_message'] = "You are not enrolled in this course.";
        header("Location: profile.php");
        exit();
    }

    // Delete the enrollment
    $stmt_unenroll = $conn->prepare("DELETE FROM enrollments WHERE student_id = ? AND course_id = ?");
    $stmt_unenroll->bind_param("ii", $student_id, $course_id);
    if ($stmt_unenroll->execute()) {
        $_SESSION['success_message'] = "You have successfully unenrolled from the course.";
    } else {
        $_SESSION['error_message'] = "An error occurred while unenrolling. Please try again.";
    }

    header("Location: profile.php");
    exit();
}
?>
