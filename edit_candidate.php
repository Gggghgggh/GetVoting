<?php
session_start();
if (!isset($_SESSION['adminLoggedin'])) {
    header("location: adminLogin.php");
    exit();
}

$id = $_GET['id'] ?? null;
if ($id) {
    $conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $voteCount = (int)$_POST['voteCount'];

        $update_query = "UPDATE candidate SET name = '$name', email = '$email', contact = '$contact', voteCount = $voteCount WHERE id = $id";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['message'] = 'Candidate details updated successfully.';
        } else {
            $_SESSION['message'] = 'Failed to update candidate details.';
        }
        header("location: list_candidates.php");
        exit();
    } else {
        $query = "SELECT * FROM candidate WHERE id = $id";
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $candidate = mysqli_fetch_assoc($result);

        if (!$candidate) {
            die("Candidate not found.");
        }
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
        <h1 class="my-4">Edit Candidate</h1>
        <form method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($candidate['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($candidate['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($candidate['contact']); ?>" required>
            </div>
            <div class="form-group">
                <label for="voteCount">Vote Count</label>
                <input type="number" class="form-control" id="voteCount" name="voteCount" value="<?php echo htmlspecialchars($candidate['voteCount']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
        <a href="list_candidates.php" class="btn btn-secondary mt-3">Back to List</a>
    </div>
</body>
</html>
