<?php
session_start();

// Check if the user is logged in using 'vid'
if (!isset($_SESSION['vid'])) {
    header("Location: voterLogin.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "studentVote");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get logged-in user's ID from session
$user_id = $_SESSION['vid'];

// If form is submitted, update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $studentId = $_POST['studentId'];
    $mobileNumber = $_POST['mobileNumber'];

    // File upload logic for profile photo
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        // Sanitize file name
        $file_name = basename($_FILES["profile_photo"]["name"]);
        $file_name_sanitized = preg_replace('/[^A-Za-z0-9_\.-]/', '_', $file_name);

        // Ensure uploads directory exists
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);  // Create uploads/ directory if it doesn't exist
        }

        // Define the target file path
        $target_file = $target_dir . $file_name_sanitized;

        // Move the uploaded file
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            // Update query with profile photo
            $sql = "UPDATE users SET name=?, studentId=?, mobileNumber=?, profile_photo=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssi", $name, $studentId, $mobileNumber, $target_file, $user_id);
        } else {
            echo "Error uploading the file.";
        }
    } else {
        // Update query without profile photo
        $sql = "UPDATE users SET name=?, studentId=?, mobileNumber=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $name, $studentId, $mobileNumber, $user_id);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='color: green;'>Profile updated successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error updating profile: " . mysqli_error($conn) . "</p>";
    }

    mysqli_stmt_close($stmt);
}

// Query to get the logged-in user's details
$sql = "SELECT name, studentId, mobileNumber, profile_photo FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $name = $row['name'];
    $studentId = $row['studentId'];
    $mobileNumber = $row['mobileNumber'];
    $profile_photo = $row['profile_photo'];
} else {
    echo "<p style='color: red;'>User details not found.</p>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .profile-container {
            width: 100%;
            max-width: 500px;
            margin: 30px auto;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 2px solid #ddd;
        }
        h2 {
            margin: 0 0 20px;
            font-size: 24px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            align-self: flex-start;
        }
        input[type="text"],
        input[type="file"] {
            width: 100%;
            max-width: 400px;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            margin: 0;
        }
        .links .button {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #6f42c1;
            color: #ffffff;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            gap: 8px;
        }
        .links .button:hover {
            background-color: #5a2d8f;
        }
        .links .button i {
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="links">
            <a href="logout.php" class="button"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
<div class="profile-container">
    <h2>Edit Profile</h2>

    <?php if (isset($profile_photo) && !empty($profile_photo)): ?>
        <img src="<?php echo htmlspecialchars($profile_photo); ?>" alt="Profile Photo" class="profile-photo">
    <?php else: ?>
        <img src="default_profile.png" alt="Default Profile" class="profile-photo">
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

        <label for="studentId">Student ID:</label>
        <input type="text" id="studentId" name="studentId" value="<?php echo htmlspecialchars($studentId); ?>" required>

        <label for="mobileNumber">Mobile Number:</label>
        <input type="text" id="mobileNumber" name="mobileNumber" value="<?php echo htmlspecialchars($mobileNumber); ?>" required>

        <label for="profile_photo">Profile Photo:</label>
        <input type="file" id="profile_photo" name="profile_photo">

        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>
