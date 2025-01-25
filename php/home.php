<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

include '../db/db.php';

// Fetch logged-in user details
$student_id = $_SESSION['student_id'];
$query = "SELECT * FROM students WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// Fetch all courses
$query_courses = "SELECT * FROM courses";
$result_courses = $conn->query($query_courses);
$courses = $result_courses->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            width: 100%;
            max-width: 520px;
            /* Adjust the card width */
            height: 380px;
            /* Adjust the card height */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin: 0 auto;
            /* Center align cards in smaller devices */
        }

        .card-img-top {
            height: 200px;
            /* Restrict image height */
            object-fit: cover;
            /* Maintain aspect ratio */
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .navbar-brand {
            
            
        }

       

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .modal-header {
            background-color: #007bff;
            color: #ffffff;
        }

        .modal-title {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include('nav_bar.php'); ?>

    <div class="container mt-4">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <h2 class="mt-4 text-center">Available Courses</h2>
        <p class="text-center text-muted">Explore and enroll in your preferred courses below.</p>

        <div class="row mt-4">
            <?php foreach ($courses as $course): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <?php if (!empty($course['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($course['image_path']); ?>" class="card-img-top"
                                alt="Course Image">
                        <?php else: ?>
                            <img src="default_image.jpg" class="card-img-top" alt="Default Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($course['description']); ?></p>
                            <button class="btn btn-primary w-100"
                                onclick="openEnrollModal(<?php echo $course['course_id']; ?>, '<?php echo htmlspecialchars($course['course_name']); ?>')">Select</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal for Enrolling -->
    <div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="enrollForm" method="POST" action="enroll.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="enrollModalLabel">Enroll in Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="courseId" name="course_id">
                        <div class="mb-3">
                            <label for="courseName" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="courseName" name="course_name" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Enroll</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEnrollModal(courseId, courseName) {
            document.getElementById('courseId').value = courseId;
            document.getElementById('courseName').value = courseName;
            new bootstrap.Modal(document.getElementById('enrollModal')).show();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>