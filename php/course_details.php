<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

include '../db/db.php';

$course_id = $_GET['course_id'];

// Fetch course details
$stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include('nav_bar.php'); ?>

    <div class="container mt-3">
        <h4 class="mt-4">Course Details</h4>
        <div class="card" style="border: 1px solid black;">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                <p><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?></p>
                <p><strong>Content:</strong> <?php echo htmlspecialchars($course['content']); ?></p>
                <p><strong>Level:</strong> <?php echo htmlspecialchars($course['level']); ?></p>
                <p><strong>Price:</strong> <?php echo htmlspecialchars($course['price']);?> USD</p>

                
                <a href="" class="btn btn-primary">Start the Course</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
