<?php
session_start();
if (!isset($_SESSION['adminLoggedin'])) {
    header("location: adminLogin.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs to prevent SQL Injection
    $election_name = mysqli_real_escape_string($conn, $_POST['election_name']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $max_votes = (int)$_POST['max_votes'];

    // Insert or update the settings
    $update_query = "
        INSERT INTO settings (election_name, start_date, start_time, end_date, end_time, max_votes)
        VALUES ('$election_name', '$start_date', '$start_time', '$end_date', '$end_time', $max_votes)
        ON DUPLICATE KEY UPDATE
        election_name = '$election_name',
        start_date = '$start_date',
        start_time = '$start_time',
        end_date = '$end_date',
        end_time = '$end_time',
        max_votes = $max_votes
    ";

    // Execute query and set session message
    if (mysqli_query($conn, $update_query)) {
        // Success message
        $_SESSION['message'] = "Settings updated successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        // Error message
        $_SESSION['message'] = "Error updating settings: " . mysqli_error($conn);
        $_SESSION['msg_type'] = "danger";
    }
}

// Close the database connection
mysqli_close($conn);

// Redirect back to the settings page to display the message
header("Location: settings.php");
exit();
?>
