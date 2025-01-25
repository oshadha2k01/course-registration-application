<?php
session_start();
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_SESSION['student_id'];
    $course_id = $_POST['course_id'];

    // Check if the student is already enrolled in the course
    $stmt_check_enrollment = $conn->prepare("SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?");
    $stmt_check_enrollment->bind_param("ii", $student_id, $course_id);
    $stmt_check_enrollment->execute();
    $result_enrollment = $stmt_check_enrollment->get_result();

    if ($result_enrollment->num_rows > 0) {
        // Redirect to home.php with an error message
        $_SESSION['error_message'] = "You are already enrolled in this course.";
        header("Location: home.php");
        exit();
    }

    // Verify that the student details match the session data
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the student exists and if the details match
    if ($result->num_rows === 0) {
        die("Student not found. Please log in and try again.");
    } else {
        $student = $result->fetch_assoc();
        
        
    }

    // Insert enrollment data into the enrollments table
    $stmt = $conn->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $student_id, $course_id);
    if ($stmt->execute()) {
        // Redirect to course details page after successful enrollment
        header("Location: course_details.php?course_id=$course_id");
        exit();
    } else {
        die("Error enrolling in course. Please try again.");
    }
}
?>
