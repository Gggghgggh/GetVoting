<?php 
session_start();
if(!isset($_SESSION['adminLoggedin'])) {
    header("location: adminLogin.php");
    exit();
}

if(isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));
    
    $query = "SELECT * FROM users WHERE id = $userId";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    if($row = mysqli_fetch_array($result)) {
        // Store profile photo path, if it exists
        $profile_photo = !empty($row['profile_photo']) ? $row['profile_photo'] : 'default_profile.png'; // Fallback to a default image if not set
        ?>
        <h1>User Details</h1>

        <!-- Profile Photo -->
        <div class="profile-photo-container">
            <img src="<?php echo $profile_photo; ?>" alt="Profile Photo" class="profile-photo">
        </div>

        <table class="table table-striped table-bordered table-hover">
            <tr><th>ID</th><td><?php echo $row['id']; ?></td></tr>
            <tr><th>Name</th><td><?php echo $row['name']; ?></td></tr>
            <tr><th>Student ID</th><td><?php echo $row['studentId']; ?></td></tr>
            <tr><th>Mobile Number</th><td><?php echo $row['mobileNumber']; ?></td></tr>
            <tr><th>Voted</th><td><?php echo $row['voted'] ? 'Yes' : 'No'; ?></td></tr>
        </table>
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
