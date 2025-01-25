<?php
include '../db/db.php'; // Include your database connection

// Fetch all courses from the database
$query = "SELECT * FROM courses";
$result = $conn->query($query);

// Check if the success message exists in the URL
$success_message = '';
if (isset($_GET['success'])) {
    $success_message = $_GET['success'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            object-fit: cover;
            height: 200px;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        .course-actions {
            margin-top: 1rem;
        }

        .custom-card-margin {
            margin-top: 80px;
            /* Adjust this value as needed */
        }
    </style>
</head>

<body>
    <?php include('admin_nav_bar.php'); ?>

    <div class="container mt-4">
        <!-- Success Message Display -->
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Courses Grid -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php while ($course = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card shadow-sm custom-card-margin">
                        <!-- Display course image -->
                        <img src="../uploads/<?php echo htmlspecialchars($course['image_path']); ?>" class="card-img-top"
                            alt="Course Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                            <!-- Action Buttons -->
                            <div class="course-actions d-flex justify-content-between">
                                <a href="admin_course_details.php?id=<?php echo $course['course_id']; ?>"
                                    class="btn btn-primary btn-md btn-custom">View</a>
                                <a href="admin_course_edit.php?id=<?php echo $course['course_id']; ?>"
                                    class="btn btn-warning btn-md btn-custom">Edit</a>
                                <a href="admin_course_delete.php?id=<?php echo $course['course_id']; ?>"
                                    class="btn btn-danger btn-md btn-custom">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto dismiss success message after 3 seconds -->
    <script>
        setTimeout(function () {
            var alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, 3000); // Hide after 3 seconds
    </script>
</body>

</html>