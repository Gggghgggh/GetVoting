<?php
session_start();
if(!isset($_SESSION['adminLoggedin'])) 
{ 
   header("location: adminLogin.php"); 
   exit();
}

if(isset($_GET['id']) && isset($_GET['status'])) {
    $userId = intval($_GET['id']);
    $status = intval($_GET['status']);
    $conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

    $updateQuery = "UPDATE users SET status = $status WHERE id = $userId";
    if(mysqli_query($conn, $updateQuery)) {
        header("Location: list_users.php"); // Redirect back to user list after updating status
    } else {
        echo "Error updating status.";
    }
} else {
    echo "Invalid request.";
}
?>
