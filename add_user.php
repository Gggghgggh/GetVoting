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
    $studentId = mysqli_real_escape_string($conn, $_POST['studentId']);
    $pass_word = mysqli_real_escape_string($conn, $_POST['pass_word']); // Ideally, hash this password
    $mobileNumber = mysqli_real_escape_string($conn, $_POST['mobileNumber']);

    // Insert new user into database
    $insert_query = "INSERT INTO users (name, studentId, pass_word, mobileNumber) VALUES ('$name', '$studentId', '$pass_word', '$mobileNumber')";
    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['message'] = 'New user added successfully.';
    } else {
        $_SESSION['message'] = 'Failed to add new user: ' . mysqli_error($conn);
    }
    header("location: list_candidates.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Secular+One" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Add New User</h1>
        <form method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="studentId">Student ID</label>
                <input type="text" class="form-control" id="studentId" name="studentId" required>
            </div>
            <div class="form-group">
                <label for="pass_word">Password</label>
                <input type="password" class="form-control" id="pass_word" name="pass_word" required>
            </div>
            <div class="form-group">
                <label for="mobileNumber">Mobile Number</label>
                <input type="text" class="form-control" id="mobileNumber" name="mobileNumber" required>
            </div>
            <button type="submit" class="btn btn-primary">Add User</button>
        </form>
        <a href="list_candidates.php" class="btn btn-secondary mt-3">Back to List</a>
    </div>
</body>
</html>
