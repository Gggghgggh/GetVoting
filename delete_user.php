<?php 
session_start();
if(!isset($_SESSION['adminLoggedin'])) {
    header("location: adminLogin.php");
    exit();
}

if(isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

    $query = "DELETE FROM users WHERE id = $userId";
    if(mysqli_query($conn, $query)) {
        echo "<h3>User deleted successfully.</h3>";
    } else {
        echo "<h3>Error deleting user.</h3>";
    }
} else {
    echo "<h3>Invalid User ID.</h3>";
}
?>

<a href="user_list.php" class="btn">Back to User List</a>

<style type="text/css">
    body {
        padding-left: 10%;
        padding-right: 10%;
        text-align: center;
        font-family: "Secular One", serif;
    }
    h3 {
        font-size: 24px;
        color: red;
    }
    .btn {
        padding: 5px 15px;
        background-color: #00ffd2;
    }
</style>
