<?php
session_start();
include '../db/db.php';

// Fetch statistics
$total_users_query = "SELECT COUNT(*) AS total_users FROM students WHERE role = 'user'";
$total_admins_query = "SELECT COUNT(*) AS total_admins FROM students WHERE role = 'admin'";
$total_courses_query = "SELECT COUNT(*) AS total_courses FROM courses";
$total_enrollments_query = "SELECT COUNT(*) AS total_enrollments FROM enrollments";

$total_users = $conn->query($total_users_query)->fetch_assoc()['total_users'] ?? 0;
$total_admins = $conn->query($total_admins_query)->fetch_assoc()['total_admins'] ?? 0;
$total_courses = $conn->query($total_courses_query)->fetch_assoc()['total_courses'] ?? 0;
$total_enrollments = $conn->query($total_enrollments_query)->fetch_assoc()['total_enrollments'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .add-course-btn {
            position: absolute;
            top: 70px;
            right: 20px;
        }

        .admin-title {
            text-align: center;
            margin-top: 10px;
            font-weight: 500;
            color: #333;

        }

        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin: 0;

        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include('admin_nav_bar.php'); ?>
    <div class="container mt-5">
        <h2 class="admin-title">Admin Dashboard</h2>
        

        <div class="row text-center mt-4">
            <!-- Total Users Card -->
            <div class="col-md-3">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Admins Card -->
            <div class="col-md-3">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Admins</h5>
                        <p class="card-text"><?php echo $total_admins; ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Courses Card -->
            <div class="col-md-3">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Courses</h5>
                        <p class="card-text"><?php echo $total_courses; ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Enrollments Card -->
            <div class="col-md-3">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Enrollments</h5>
                        <p class="card-text"><?php echo $total_enrollments; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row for Pie Charts -->
        <div class="row mt-4 justify-content-center">
            <!-- User Roles Pie Chart -->
            <div class="col-md-4 text-center">
                <div class="card bg-light mb-3">
                    <div class="card-body" style="width: 400px; margin: 0 auto;">
                        <h5 class="card-title text-center">User Roles Distribution</h5>
                        <canvas id="userRolesChart" style="max-width: 400px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Courses vs Enrollments Pie Chart -->
            <div class="col-md-4 text-center">
                <div class="card bg-light mb-3">
                    <div class="card-body" style="width: 400px; margin: 0 auto;">
                        <h5 class="card-title text-center">Courses vs Enrollments</h5>
                        <canvas id="coursesEnrollmentsChart" style="max-width: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // User Roles Pie Chart
            const ctxUserRoles = document.getElementById('userRolesChart').getContext('2d');
            new Chart(ctxUserRoles, {
                type: 'pie',
                data: {
                    labels: ['Users', 'Admins'],
                    datasets: [{
                        label: 'User Roles',
                        data: [<?php echo $total_users; ?>, <?php echo $total_admins; ?>],
                        backgroundColor: ['#007bff', '#ffc107']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 0,
                            bottom: 0,
                        }
                    }
                }
            });

            // Courses vs Enrollments Pie Chart
            const ctxCoursesEnrollments = document.getElementById('coursesEnrollmentsChart').getContext('2d');
            new Chart(ctxCoursesEnrollments, {
                type: 'pie',
                data: {
                    labels: ['Courses', 'Enrollments'],
                    datasets: [{
                        label: 'Courses vs Enrollments',
                        data: [<?php echo $total_courses; ?>, <?php echo $total_enrollments; ?>],
                        backgroundColor: ['#28a745', '#17a2b8']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 0,
                            bottom: 0,
                        }
                    }
                }
            });
        </script>
    </div>

</body>

</html>