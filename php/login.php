<?php
session_start();
include '../db/db.php';

$email = $password = "";
$email_error = $password_error = "";
$success_message = $error_message = "";  // Variables for success and error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Enter a valid email address.";
    }

    // Validate Password
    if (empty($password)) {
        $password_error = "Password is required.";
    }

    // Process login if no errors
    if (empty($email_error) && empty($password_error)) {
        $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $student = $result->fetch_assoc();
            if (password_verify($password, $student['password'])) {
                $_SESSION['student_id'] = $student['student_id'];
                $_SESSION['first_name'] = $student['first_name'];
                
                if ($stmt->execute()) {
                    $success_message = "Login successful!";
                    echo "<script>
                        setTimeout(function() {
                            window.location.href = 'home.php';
                        }, 1000);
                      </script>";
                } else {
                    $error_message = "Something went wrong. Please try again.";
                }
            } else {
                $password_error = "Invalid password. Please try again.";
            }
        } else {
            $email_error = "No account found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error {
            border-color: red !important;
        }

        .error-message {
            color: red;
            font-size: 0.875rem;
        }

        .form-container {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .form-header {
            margin-bottom: 20px;
        }

        .input-group-text {
            cursor: pointer;
        }

        .login-link {
            text-decoration: none;
            color: #007bff;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #e9ecef;">
    <div class="form-container">
        <h2 class="text-center form-header">Login</h2>

        <?php
        if (!empty($success_message)) {
            echo "<div class='alert alert-success'>$success_message</div>";
        }
        if (isset($email_error) && $email_error != "") {
            echo "<div class='alert alert-danger'>$email_error</div>";
        }
        if (isset($password_error) && $password_error != "") {
            echo "<div class='alert alert-danger'>$password_error</div>";
        }
        ?>

        <form method="POST" action="login.php" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control <?php echo !empty($email_error) ? 'error' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <div class="error-message"><?php echo $email_error; ?></div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control <?php echo !empty($password_error) ? 'error' : ''; ?>" id="password" name="password" required>
                    <span class="input-group-text" onclick="togglePasswordVisibility('password')">&#128065;</span>
                </div>
                <div class="error-message"><?php echo $password_error; ?></div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <!-- Login link -->
        <div class="mt-3 text-center">
            <p>Don't have an account? <a href="register.php" class="login-link">Click here to register</a></p>
        </div>
    </div>

    <script>
        // Toggle Password Visibility with Time Limit
        function togglePasswordVisibility(id) {
            const field = document.getElementById(id);
            field.type = field.type === "password" ? "text" : "password";

            // Set a timeout to hide the password again after 3 seconds (3000 ms)
            if (field.type === "text") {
                setTimeout(function() {
                    field.type = "password";
                }, 2000); // Hide password after 3 seconds
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
