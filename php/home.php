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
</head>
<body>
    <!-- Navbar -->
    <?php include('nav_bar.php'); ?>

    <div class="container mt-3">
        <!-- Display error message if exists -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?> <!-- Clear the error message -->
        <?php endif; ?>

        <h4 class="mt-4">Available Courses</h4>
        <div class="row mt-3">
            <?php
            // Assuming $courses contains the list of courses
            foreach ($courses as $course): ?>
                <div class="col-md-4 mb-3">
                    <div class="card" style="border: 1px solid black;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                            <button class="btn btn-primary" onclick="openEnrollModal(<?php echo $course['course_id']; ?>, '<?php echo htmlspecialchars($course['course_name']); ?>')">Select</button>
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
                        <div class="mb-3">
                            <label for="confirmFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="confirmFirstName" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmLastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="confirmLastName" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="confirmEmail" name="email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enroll</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEnrollModal(courseId, courseName) {
            document.getElementById('courseId').value = courseId;
            document.getElementById('courseName').value = courseName;
            document.getElementById('courseName').disabled = true;
            document.getElementById('enrollModalLabel').textContent = `Enroll in ${courseName}`;
            new bootstrap.Modal(document.getElementById('enrollModal')).show();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
