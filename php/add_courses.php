<?php
include '../db/db.php'; // Include your database connection

// Initialize error and success messages
$errors = [];
$success_message = '';
$image_path = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $course_name = trim($_POST['course_name']);
    $description = trim($_POST['description']);
    $duration = trim($_POST['duration']);
    $content = trim($_POST['content']);
    $level = trim($_POST['level']);
    $price = trim($_POST['price']);

    // Validate form fields
    if (empty($course_name)) {
        $errors['course_name'] = "Course name is required.";
    }
    if (empty($description)) {
        $errors['description'] = "Description is required.";
    }
    if (empty($duration)) {
        $errors['duration'] = "Duration is required.";
    }
    if (empty($content)) {
        $errors['content'] = "Content is required.";
    }
    if (empty($level)) {
        $errors['level'] = "Level is required.";
    }
    if (empty($price)) {
        $errors['price'] = "Price is required.";
    } elseif (!is_numeric($price) || $price < 0) {
        $errors['price'] = "Price must be a valid positive number.";
    }

    // Process the image file if uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = $_FILES['image']['name'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_size = $_FILES['image']['size'];
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            // Define allowed extensions and max size
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $max_size = 2 * 1024 * 1024; // 2MB

            if (!in_array($image_ext, $allowed_extensions)) {
                $errors['image'] = "Invalid image format. Only JPG, PNG, and GIF are allowed.";
            } elseif ($image_size > $max_size) {
                $errors['image'] = "Image size exceeds 2MB.";
            } else {
                // Define upload directory
                $upload_dir = '../uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true); // Create directory if not exists
                }

                $new_image_name = uniqid() . '.' . $image_ext;
                $image_path = $upload_dir . $new_image_name;

                if (!move_uploaded_file($image_tmp_name, $image_path)) {
                    $errors['image'] = "Failed to upload the image.";
                }
            }
        } else {
            $errors['image'] = "An error occurred during the image upload.";
        }
    } elseif (empty($image_path)) {
        $errors['image'] = "Image is required.";
    }

    // Insert into database if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO courses (course_name, description, duration, content, level, price, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $course_name, $description, $duration, $content, $level, $price, $image_path);

        if ($stmt->execute()) {
            $success_message = "Course added successfully!";
            // Clear the form fields
            $course_name = $description = $duration = $content = $level = $price = '';
        } else {
            $errors['general'] = "Failed to add the course. Please try again.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            /* Light gray background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background-color: #ffffff;
            /* White card */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }

        .is-invalid {
            border: 1px solid red;
        }

        .error {
            color: red;
            font-size: 0.9em;
        }

        .success {
            color: green;
            font-size: 1em;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include('admin_nav_bar.php'); ?>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="form-container">
            <h1 class="text-center mb-4 ">Add Course</h1>
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"> <?php echo $success_message; ?> </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <!-- First Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Course Image</label>
                            <input type="file" name="image" id="image"
                                class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>">
                            <?php if (isset($errors['image'])): ?>
                                <div class="error"> <?php echo $errors['image']; ?> </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="course_name" class="form-label">Course Name</label>
                            <input type="text" name="course_name" id="course_name"
                                class="form-control <?php echo isset($errors['course_name']) ? 'is-invalid' : ''; ?>"
                                value="<?php echo htmlspecialchars($course_name ?? ''); ?>">
                            <?php if (isset($errors['course_name'])): ?>
                                <div class="error"> <?php echo $errors['course_name']; ?> </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration</label>
                            <input type="text" name="duration" id="duration"
                                class="form-control <?php echo isset($errors['duration']) ? 'is-invalid' : ''; ?>"
                                value="<?php echo htmlspecialchars($duration ?? ''); ?>">
                            <?php if (isset($errors['duration'])): ?>
                                <div class="error"> <?php echo $errors['duration']; ?> </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="level" class="form-label">Level</label>
                            <select name="level" id="level"
                                class="form-control <?php echo isset($errors['level']) ? 'is-invalid' : ''; ?>">
                                <option value="">Select Level</option>
                                <option value="Beginner" <?php echo isset($level) && $level == 'Beginner' ? 'selected' : ''; ?>>Beginner</option>
                                <option value="Intermediate" <?php echo isset($level) && $level == 'Intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                                <option value="Advanced" <?php echo isset($level) && $level == 'Advanced' ? 'selected' : ''; ?>>Advanced</option>
                            </select>
                            <?php if (isset($errors['level'])): ?>
                                <div class="error"> <?php echo $errors['level']; ?> </div>
                            <?php endif; ?>
                        </div>


                    </div>

                    <!-- Second Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description"
                                class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                            <?php if (isset($errors['description'])): ?>
                                <div class="error"> <?php echo $errors['description']; ?> </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea name="content" id="content"
                                class="form-control <?php echo isset($errors['content']) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($content ?? ''); ?></textarea>
                            <?php if (isset($errors['content'])): ?>
                                <div class="error"> <?php echo $errors['content']; ?> </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" name="price" id="price"
                                class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>"
                                value="<?php echo htmlspecialchars($price ?? ''); ?>">
                            <?php if (isset($errors['price'])): ?>
                                <div class="error"> <?php echo $errors['price']; ?> </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary w-100">Add Course</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const formFields = document.querySelectorAll("input, textarea, select");

            formFields.forEach(field => {
                field.addEventListener("input", () => {
                    const errorDiv = field.parentElement.querySelector(".error");
                    if (errorDiv) {
                        errorDiv.textContent = "";
                    }
                    field.classList.remove("is-invalid");
                });
            });

            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>