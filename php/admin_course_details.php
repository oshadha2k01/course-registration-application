<?php
include '../db/db.php'; // Include database connection

// Check if the course ID is passed in the URL
if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']); // Sanitize the course ID

    // Fetch course details from the database
    $query = "SELECT * FROM courses WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the course exists
    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
    } else {
        // Redirect if the course is not found
        header("Location: admin_course_view.php?success=Course not found");
        exit();
    }
} else {
    // Redirect if no course ID is provided
    header("Location: admin_course_view.php?success=Invalid course selection");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style for course image */
        .card-img-top {
            object-fit: cover;
            /* Ensures the image fills the defined dimensions */
            height: 250px;
            /* Adjust to desired height */
            width: 100%;
            /* Makes the image responsive */
            border-radius: 5px;
            /* Rounded corners */
        }

        /* Additional styling for the card */
        .card {
            border: 1px solid #ddd;
            /* Light border */
            border-radius: 12px;
            /* Slightly more rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Subtle shadow effect */
            transition: transform 0.2s ease-in-out;
            /* Animation effect */
        }

        .card:hover {
            transform: scale(1.02);
            /* Slight zoom on hover */
        }

        .card-title {
            font-size: 22px;
            /* Adjust font size for title */
            font-weight: bold;
            color: #2c3e50;
            /* Darker text color for readability */
        }

        .card-text {
            font-size: 16px;
            /* Adjust font size for text */
            color: #555;
            /* Neutral color for better readability */
        }

        /* Back button styling */
        .btn-secondary {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .btn-secondary:hover {
            background-color: #484f54;
            border-color: #3e454a;
        }

        /* Responsive container */
        .container {
            max-width: 800px;
            /* Center the content */
        }

        @media (max-width: 768px) {
            .card-img-top {
                height: 200px;
                /* Adjust image height for smaller screens */
            }

            .card-title {
                font-size: 20px;
            }

            .card-text {
                font-size: 14px;
            }

            .course-title {
                margin-top: 30px;
                /* Adjusts the top margin */
                font-size: 36px;
                /* Increases the font size */
                font-weight: bold;
                /* Optional: makes the text bold */
                color: #2c3e50;
                /* Dark color for better readability */
            }

        }
    </style>
</head>

<body>
    <?php include('admin_nav_bar.php'); ?>

    <div class="container mt-5">
    <h1 class="text-center mb-4">Course Details</h1>
        <div class="card mx-auto">
            <img src="../uploads/<?php echo htmlspecialchars($course['image_path']); ?>" class="card-img-top"
                alt="Course Image">
            <div class="card-body">
                <h5 class="card-title text-center"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                <p class="card-text"><strong>Description:</strong>
                    <?php echo htmlspecialchars($course['description']); ?></p>
                <p class="card-text"><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?></p>
                <p class="card-text"><strong>Level:</strong> <?php echo htmlspecialchars($course['level']); ?></p>
                <p class="card-text"><strong>Content:</strong> <?php echo htmlspecialchars($course['content']); ?></p>
                <p class="card-text"><strong>Price:</strong> $<?php echo number_format($course['price'], 2); ?></p>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="admin_course_view.php" class="btn btn-secondary px-4 py-2">Back to Courses</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>