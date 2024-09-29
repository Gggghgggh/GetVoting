<?php 
session_start();
if(!isset($_SESSION['adminLoggedin'])) {
    header("location: adminLogin.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

if(isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    if(isset($_POST['update'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $studentId = mysqli_real_escape_string($conn, $_POST['studentId']);
        $mobileNumber = mysqli_real_escape_string($conn, $_POST['mobileNumber']);
        $voted = isset($_POST['voted']) ? 1 : 0;

        // Handle password update
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);
        
        if (!empty($password) && $password === $confirmPassword) {
            $passwordUpdate = ", pass_word = '$password'";
        } else if (!empty($password) || !empty($confirmPassword)) {
            echo "<h3>Passwords do not match or are empty.</h3>";
            $passwordUpdate = "";
        } else {
            $passwordUpdate = "";
        }

        // Update user information
        $updateQuery = "UPDATE users SET name = '$name', studentId = '$studentId', mobileNumber = '$mobileNumber', voted = $voted" 
                     . $passwordUpdate 
                     . " WHERE id = $userId";
        if(mysqli_query($conn, $updateQuery)) {
            echo "<h3>User updated successfully.</h3>";
        } else {
            echo "<h3>Error updating user.</h3>";
        }
    }

    $query = "SELECT * FROM users WHERE id = $userId";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    if($row = mysqli_fetch_array($result)) {
        ?>
        <h1>Edit User</h1>
        <form method="POST" action="">
            <div class="profile-photo-container">
                <img src="<?php echo !empty($row['profile_photo']) ? $row['profile_photo'] : 'uploads/default_profile.png'; ?>" alt="Profile Photo" class="profile-photo">
            </div>
            <table class="table table-striped table-bordered table-hover">
                <tr><th>Name</th><td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"></td></tr>
                <tr><th>Student ID</th><td><input type="text" name="studentId" value="<?php echo htmlspecialchars($row['studentId']); ?>"></td></tr>
                <tr><th>Mobile Number</th><td><input type="text" name="mobileNumber" value="<?php echo htmlspecialchars($row['mobileNumber']); ?>"></td></tr>
                <tr><th>Voted</th><td><input type="checkbox" name="voted" <?php echo $row['voted'] ? 'checked' : ''; ?>></td></tr>
                <tr><th>New Password</th><td><input type="password" name="password" placeholder="Enter new password"></td></tr>
                <tr><th>Confirm Password</th><td><input type="password" name="confirm_password" placeholder="Confirm new password"></td></tr>
            </table>
            <input type="submit" name="update" value="Update" class="btn">
        </form>
        <a href="user_list.php" class="btn">Back to User List</a>
        <?php
    } else {
        echo "<h3>User not found.</h3>";
    }
} else {
    echo "<h3>Invalid User ID.</h3>";
}
?>

<style type="text/css">
    body {
        padding-left: 10%;
        padding-right: 10%;
        text-align: center;
        font-family: "Secular One", serif;
    }
    table {
        text-align: left;
        margin: 0 auto;
    }
    h1 {
        font-size: 36px;
        font-family: "Secular One", serif;
        color: aqua;
    }
    .btn {
        padding: 5px 15px;
        background-color: #00ffd2;
    }
    .profile-photo-container {
        text-align: center;
        margin-bottom: 20px;
    }
    .profile-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>
