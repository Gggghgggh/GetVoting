<?php
session_start();
if (!isset($_SESSION['adminLoggedin'])) {
    header("location: adminLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

    // Retrieve and sanitize form input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    
    // Handle image upload
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/"; // Ensure this directory exists and is writable
    $target_file = $target_dir . basename($image);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an actual image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        $_SESSION['message'] = 'File is not an image.';
        header("location: add_candidate.php");
        exit();
    }

    // Check file size (limit: 5MB)
    if ($_FILES['image']['size'] > 5000000) {
        $_SESSION['message'] = 'Sorry, your file is too large.';
        header("location: add_candidate.php");
        exit();
    }

    // Allow only certain formats
    $allowed_formats = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_formats)) {
        $_SESSION['message'] = 'Sorry, only JPG, JPEG, PNG, and GIF files are allowed.';
        header("location: add_candidate.php");
        exit();
    }

    // Try to upload file
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $_SESSION['message'] = 'Sorry, there was an error uploading your file.';
        header("location: add_candidate.php");
        exit();
    }

    // Insert candidate details along with image path into database
    $insert_query = "INSERT INTO candidate (name, email, contact, image) VALUES ('$name', '$email', '$contact', '$target_file')";
    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['message'] = 'New candidate added successfully.';
    } else {
        $_SESSION['message'] = 'Failed to add new candidate: ' . mysqli_error($conn);
    }
    header("location: list_candidates.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Add Candidate</h1>
        <form method="post" enctype="multipart/form-data"> <!-- Important: enctype added -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact</label>
                <input type="tel" class="form-control" id="contact" name="contact" required>
            </div>
            <div class="form-group">
                <label for="image">Candidate Image</label>
                <input type="file" class="form-control-file" id="image" name="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Candidate</button>
        </form>
        <a href="list_candidates.php" class="btn btn-secondary mt-3">Back to List</a>
    </div>
</body>
</html>
