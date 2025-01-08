<?php
// Include database connection
include '../db/db.php';

// Initialize variables and error messages
$first_name = $last_name = $email = $password = $confirm_password = "";
$first_name_error = $last_name_error = $email_error = $password_error = $confirm_password_error = "";
$success_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input values
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Validate first name
    if (empty($first_name)) {
        $first_name_error = "First name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $first_name)) {
        $first_name_error = "First name must contain only letters.";
    }

    // Validate last name
    if (empty($last_name)) {
        $last_name_error = "Last name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $last_name)) {
        $last_name_error = "Last name must contain only letters.";
    }

    // Validate email
    if (empty($email)) {
        $email_error = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $email_error = "Email already exists.";
        }
    }

    // Validate password
    if (empty($password)) {
        $password_error = "Password is required.";
    } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*])[a-zA-Z\d!@#$%^&*]{8,}$/", $password)) {
        $password_error = "Password must be at least 8 characters long, include 1 digit, 1 uppercase, 1 lowercase, and 1 special character.";
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $confirm_password_error = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $confirm_password_error = "Passwords do not match.";
    }

    // If there are no validation errors, proceed to insert data
    if (empty($first_name_error) && empty($last_name_error) && empty($email_error) && empty($password_error) && empty($confirm_password_error)) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement to insert data into the database
        $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

        if ($stmt->execute()) {
            // Success message
            $success_message = "Registration successful! You can now log in.";
            // Redirect to login page after 3 seconds with a notification
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 2000);
              </script>";
        } else {
            $error_message = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
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

        .register-link {
            text-decoration: none;
            color: #007bff;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #e9ecef;">
    <div class="form-container">
        <h2 class="text-center form-header">Student Registration</h2>

        <!-- Success Notification -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Error Notification -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php" novalidate>
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control <?php echo !empty($first_name_error) ? 'error' : ''; ?>"
                    id="firstName" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                <div class="error-message"><?php echo $first_name_error; ?></div>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control <?php echo !empty($last_name_error) ? 'error' : ''; ?>"
                    id="lastName" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                <div class="error-message"><?php echo $last_name_error; ?></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control <?php echo !empty($email_error) ? 'error' : ''; ?>" id="email"
                    name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <div class="error-message"><?php echo $email_error; ?></div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control <?php echo !empty($password_error) ? 'error' : ''; ?>"
                        id="password" name="password" required>
                    <span class="input-group-text" onclick="togglePasswordVisibility('password')">&#128065;</span>
                </div>
                <div class="error-message"><?php echo $password_error; ?></div>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <input type="password"
                        class="form-control <?php echo !empty($confirm_password_error) ? 'error' : ''; ?>"
                        id="confirmPassword" name="confirm_password" required>
                    <span class="input-group-text"
                        onclick="togglePasswordVisibility('confirmPassword')">&#128065;</span>
                </div>
                <div class="error-message"><?php echo $confirm_password_error; ?></div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <!-- Register link -->
        <div class="mt-3 text-center">
            <p>Already have an account? <a href="login.php" class="register-link">Click here to login</a></p>
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
