<?php
session_start();
if(!isset($_SESSION['adminLoggedin'])) {
    header("location: adminLogin.php"); 
    exit();
}

$id = $_GET['id'] ?? null;
if ($id) {
    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

    // Fetch candidate details
    $query = "SELECT * FROM candidate WHERE id = $id";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $candidate = mysqli_fetch_assoc($result);

    if (!$candidate) {
        die("Candidate not found.");
    }
} else {
    die("Invalid candidate ID.");
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
        <h1 class="my-4">Candidate Details</h1>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td><?php echo htmlspecialchars($candidate['id']); ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo htmlspecialchars($candidate['name']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($candidate['email']); ?></td>
            </tr>
            <tr>
                <th>Contact</th>
                <td><?php echo htmlspecialchars($candidate['contact']); ?></td>
            </tr>
            <tr>
                <th>Vote Count</th>
                <td><?php echo htmlspecialchars($candidate['voteCount']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo $candidate['status'] ? 'Active' : 'Inactive'; ?></td>
            </tr>
        </table>
        <a href="admin_dashboard.php" class="btn btn-primary">Back to List</a>
    </div>
</body>
</html>
