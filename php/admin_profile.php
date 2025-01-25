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
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .profile-card h4 {
            color: #333;
            font-weight: bold;
        }

        .list-group-item {
            font-size: 1.1rem;
        }

        .btn-row {
            margin-top: 20px;
        }

        .modal-header {
            background-color: #0d6efd;
            color: #fff;
        }

        .modal-footer {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 900px;
        }

        .btn {
            width: 100%;
        }

        @media (max-width: 768px) {
            .profile-card {
                padding: 20px;
            }

            .btn {
                width: auto;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include('admin_nav_bar.php'); ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12 mb-4">
                <div class="profile-card">
                    <h4 class="text-center">Personal Information</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>First Name:</strong> <?php echo htmlspecialchars($first_name); ?></li>
                        <li class="list-group-item"><strong>Last Name:</strong> <?php echo htmlspecialchars($last_name); ?></li>
                        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></li>
                    </ul>
                    <div class="btn-row text-center">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                        <form action="delete_account.php" method="POST" class="d-inline">
                            <button type="submit" class="btn btn-danger">Delete Account</button>
                        </form>
                    </div>
                </div>
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
                            <input type="text" class="form-control" id="editFirstName" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="editLastName" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
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
