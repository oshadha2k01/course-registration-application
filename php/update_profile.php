<?php
session_start();
include '../db/db.php';

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Get the student information from the form
$student_id = $_POST['student_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];

// Update the student's profile in the database
$query_update = "UPDATE students SET first_name = ?, last_name = ?, email = ? WHERE student_id = ?";
$stmt_update = $conn->prepare($query_update);
$stmt_update->bind_param("sssi", $first_name, $last_name, $email, $student_id);
$stmt_update->execute();

// Fetch the updated student data from the database and update the session
$query_select = "SELECT first_name, last_name, email FROM students WHERE student_id = ?";
$stmt_select = $conn->prepare($query_select);
$stmt_select->bind_param("i", $student_id);
$stmt_select->execute();
$stmt_select->store_result();
$stmt_select->bind_result($updated_first_name, $updated_last_name, $updated_email);

// Check if data is found and update session
if ($stmt_select->fetch()) {
    $_SESSION['student']['first_name'] = $updated_first_name;
    $_SESSION['student']['last_name'] = $updated_last_name;
    $_SESSION['student']['email'] = $updated_email;
}

header("Location: profile.php?message=Profile updated successfully.");
exit();
?>
