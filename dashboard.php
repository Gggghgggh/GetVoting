<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['vid'])) {
    header("location: voterLogin.php");
    exit();
}

// Get the logged-in user's ID
$id = $_SESSION['vid'];

// Database connection
$db = mysqli_connect("localhost", "root", "", "StudentVote");
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE id=$id";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

// Store profile photo path, if it exists
$profile_photo = !empty($row['profile_photo']) ? $row['profile_photo'] : 'default_profile.png'; // Fallback to a default image if not set

// Fetch current settings from the database
$settings_query = "SELECT * FROM settings ORDER BY id DESC LIMIT 1";
$settings_result = mysqli_query($db, $settings_query);
$current_settings = mysqli_fetch_assoc($settings_result);

// Handle profile update if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array to hold update query parts
    $update_parts = [];

    // Compare submitted data with existing data and build query parts
    if (!empty($_POST['name']) && $_POST['name'] !== $row['name']) {
        $name = mysqli_real_escape_string($db, $_POST['name']);
        $update_parts[] = "name='$name'";
    }
    if (!empty($_POST['studentId']) && $_POST['studentId'] !== $row['studentId']) {
        $studentId = mysqli_real_escape_string($db, $_POST['studentId']);
        $update_parts[] = "studentId='$studentId'";
    }
    if (!empty($_POST['mobileNumber']) && $_POST['mobileNumber'] !== $row['mobileNumber']) {
        $mobileNumber = mysqli_real_escape_string($db, $_POST['mobileNumber']);
        $update_parts[] = "mobileNumber='$mobileNumber'";
    }

    // Handle file upload for profile photo
    if (!empty($_FILES['profile_photo']['name'])) {
        $profile_photo = $_FILES['profile_photo']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_photo);
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_file)) {
            $profile_photo = $target_file;
            $update_parts[] = "profile_photo='$profile_photo'";
        } else {
            echo "Error uploading the file.";
        }
    }

    // Construct and execute the update query if there are any updates
    if (!empty($update_parts)) {
        $update_query = "UPDATE users SET " . implode(", ", $update_parts) . " WHERE id=$id";
        if (mysqli_query($db, $update_query)) {
            echo "Profile updated successfully!";
            // Optionally redirect to avoid resubmitting the form on page refresh
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error updating profile: " . mysqli_error($db);
        }
    }
}

// Determine the current time
$current_hour = date('Y-m-d H:i:s'); // Get the current datetime

// Fetch and compare the election end time
$election_end_time = $current_settings['end_date']; // Assuming end_date is stored in datetime format

if ($current_hour > $election_end_time) {
    // If the current time is past the end date, disable voting options
    $voting_disabled = true;
} else {
    $voting_disabled = false;
}

// Greeting based on the time of day
$current_hour = date('G'); // Get the current hour in 24-hour format

if ($current_hour >= 5 && $current_hour < 12) {
    $greeting = "Good Morning";
} elseif ($current_hour >= 12 && $current_hour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter's Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            color: #495057;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #dee2e6;
            margin-right: 20px;
            position: relative;
        }
        .profile-info {
            flex: 1;
        }
        .profile-info h1 {
            margin: 0;
            font-size: 24px;
            color: #212529;
        }
        .profile-info p {
            margin: 5px 0;
            color: #6c757d;
        }
        .profile-info .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #6f42c1;
            color: #ffffff;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
            cursor: pointer;
        }
        .profile-info .button:hover {
            background-color: #5a2d8f;
        }
        .message {
            font-size: 16px;
            font-weight: bold;
            color: #dc3545;
            margin: 20px 0;
        }
        .links {
            display: flex;
            gap: 20px;
            justify-content: center;
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

        /* Profile Card Styles */
        .profile-card {
            display: none; /* Hidden by default */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            background-color: #fff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            z-index: 999;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .profile-card .close-btn {
            position: absolute;
            top: -15px;
            right: -15px;
            cursor: pointer;
            font-size: 36px;
            color: #888;
            background-color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .profile-card .close-btn:hover {
            color: #000;
            background-color: #f1f1f1;
        }
        .profile-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .profile-card .pen-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
            font-size: 20px;
            color: #6f42c1;
        }
        .profile-card .pen-icon:hover {
            color: #5a2d8f;
        }
        .overlay {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }
        .edit-form {
            display: none;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .edit-form input {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        .edit-form button {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #6f42c1;
            color: #ffffff;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            justify-content: center;  
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-header">
            <div class="profile-photo">
                <img src="<?php echo htmlspecialchars($profile_photo); ?>" alt="Profile Photo" class="profile-photo">
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($greeting . "! " . $row['name']); ?></h1>
                <p>Student ID: <?php echo htmlspecialchars($row['studentId']); ?></p>
                
                <?php 
                $voted = $row['voted'];
                if ($voted == 1) {
                    echo "<div class='message'>You have already voted.</div>";
                } else {
                    if ($voting_disabled) {
                        echo "<div class='message'>Voting period has ended. You cannot vote at this time.</div>";
                    } else {
                        echo "<div class='message'>You have not voted. <a href='vote.php' class='button'>Vote Here</a></div>";
                    }
                }
                ?>
                <span class="button" id="viewProfileBtn">View Profile</span>
            </div>
        </div>
         <!-- Display election settings -->
         <div class="settings">
            <h2>Ongoing Elections</h2>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><?php echo htmlspecialchars($current_settings['election_name']); ?></h4>
                    <p class="card-text">
                        <strong>Start Date:</strong> <?php echo htmlspecialchars($current_settings['start_date']); ?><br>
                        <strong>End Date:</strong> <?php echo htmlspecialchars($current_settings['end_date']); ?><br>
                        <strong>Max Votes per Voter:</strong> <?php echo htmlspecialchars($current_settings['max_votes']); ?>
                    </p>
                </div>
            </div>
        <div class="links">
            <a href="logout.php" class="button"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

       
        </div>
    </div>

    <!-- Profile Card -->
    <div class="overlay" id="overlay"></div>
    <div class="profile-card" id="profileCard">
        <div class="close-btn" id="closeProfileCard">&times;</div>
        <img src="<?php echo htmlspecialchars($profile_photo); ?>" alt="Profile Photo">
        <h2><?php echo htmlspecialchars($row['name']); ?></h2>
        <p>Student ID: <?php echo htmlspecialchars($row['studentId']); ?></p>
        <p>Mobile: <?php echo htmlspecialchars($row['mobileNumber']); ?></p>
        <span class="pen-icon" id="editProfileBtn"><i class="fas fa-pen"></i></span>
        
        <!-- Edit Form -->
        <div class="edit-form" id="editForm">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" placeholder="Name" required>
                <input type="text" name="studentId" value="<?php echo htmlspecialchars($row['studentId']); ?>" placeholder="Student ID" required>
                <input type="text" name="mobileNumber" value="<?php echo htmlspecialchars($row['mobileNumber']); ?>" placeholder="Mobile Number" required>
                <input type="file" name="profile_photo">
                <button type="submit" class="button">Update Profile</button>
            </form>
        </div>
    </div>

    <script>
        const viewProfileBtn = document.getElementById('viewProfileBtn');
        const profileCard = document.getElementById('profileCard');
        const closeProfileCard = document.getElementById('closeProfileCard');
        const editProfileBtn = document.getElementById('editProfileBtn');
        const editForm = document.getElementById('editForm');
        const overlay = document.getElementById('overlay');

        viewProfileBtn.addEventListener('click', () => {
            profileCard.style.display = 'block';
            overlay.style.display = 'block';
        });

        closeProfileCard.addEventListener('click', () => {
            profileCard.style.display = 'none';
            overlay.style.display = 'none';
            editForm.style.display = 'none'; // Close edit form as well
        });

        editProfileBtn.addEventListener('click', () => {
            editForm.style.display = 'block';
        });

        overlay.addEventListener('click', () => {
            profileCard.style.display = 'none';
            overlay.style.display = 'none';
        });
    </script>
</body>
</html>
