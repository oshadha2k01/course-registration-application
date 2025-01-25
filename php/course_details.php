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
    <style>
        /* Custom Styles */
        .course-image {
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .course-card {
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .course-card-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px 10px 0 0;
        }

        .course-card-body {
            padding: 25px;
        }

        .course-btn {
            width: 100%;
            font-size: 18px;
            color: white;
        }

        .course-btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .course-btn {
                font-size: 16px;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include('nav_bar.php'); ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Course Details</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card course-card">
                    <div class="card-header course-card-header text-center">
                        <h4 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column: Description and other details -->
                            <div class="col-md-6 mb-4">
                                <p><strong>Description:</strong> <?php echo htmlspecialchars($course['description']); ?></p>
                                <p><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?></p>
                                <p><strong>Content:</strong> <?php echo htmlspecialchars($course['content']); ?></p>
                                <p><strong>Level:</strong> <?php echo htmlspecialchars($course['level']); ?></p>
                                <p><strong>Price:</strong> <?php echo htmlspecialchars($course['price']); ?> USD</p>

                                <a href="#" class="btn btn-primary course-btn">Start the Course</a>
                            </div>

                            <!-- Right Column: Image -->
                            <div class="col-md-6 mb-4 text-center">
                                <?php if (!empty($course['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($course['image_path']); ?>" class="course-image img-fluid" alt="Course Image">
                                <?php else: ?>
                                    <p>No image available for this course.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Course Enrollment Confirmation (optional) -->
    <div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrollModalLabel">Confirm Enrollment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to enroll in this course?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Confirm Enrollment</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
