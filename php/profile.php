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

// Set default values and handle undefined keys
$first_name = $student['first_name'] ?? 'N/A';
$last_name = $student['last_name'] ?? 'N/A';
$email = $student['email'] ?? 'N/A';

$query_courses = "SELECT * FROM courses c INNER JOIN enrollments e ON c.course_id = e.course_id WHERE e.student_id = ?";
$stmt_courses = $conn->prepare($query_courses);
$stmt_courses->bind_param("i", $student_id);
$stmt_courses->execute();
$enrolled_courses = $stmt_courses->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-row {
            margin-top: 10px;
            display: flex;
        }
        .btn-row button {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include('nav_bar.php'); ?>

    <div class="container mt-3">
        <h3 class="text-center">My Profile</h3>
        <div class="row justify-content-center">
            <div class="col-md-6 profile-section">
                <h4 class="text-center">Personal Information</h4>
                <ul class="list-group">
                    <li class="list-group-item">First Name: <?php echo htmlspecialchars($first_name); ?></li>
                    <li class="list-group-item">Last Name: <?php echo htmlspecialchars($last_name); ?></li>
                    <li class="list-group-item">Email: <?php echo htmlspecialchars($email); ?></li>
                </ul>
                <div class="btn-row">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                    <form action="delete_account.php" method="POST">
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6 courses-section">
                <h4 class="text-center">Enrolled Courses</h4>
                <ul class="list-group">
                    <?php if (!empty($enrolled_courses)): ?>
                        <?php foreach ($enrolled_courses as $course): ?>
                            <li class="list-group-item">
                                <?php echo htmlspecialchars($course['course_name']); ?>
                                <a href="course_details.php?course_id=<?php echo $course['course_id']; ?>" class="btn btn-primary btn-sm float-end">View Details</a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-center">No courses enrolled.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="update_profile.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                        <div class="mb-3">
                            <label for="editFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="editFirstName" name="first_name" 
                                   value="<?php echo htmlspecialchars($first_name); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="editLastName" name="last_name" 
                                   value="<?php echo htmlspecialchars($last_name); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" 
                                   value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
