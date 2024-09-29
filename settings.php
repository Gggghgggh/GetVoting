<?php
session_start();
if (!isset($_SESSION['adminLoggedin'])) {
    header("location: adminLogin.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

// Fetch current settings
$query = "SELECT * FROM settings ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$current_settings = mysqli_fetch_assoc($result);

// Handle case where no settings are found
if ($current_settings === null) {
    $current_settings = [
        'election_name' => '',
        'start_date' => '',
        'end_date' => '',
        'start_time' => '00:00:00',
        'end_time' => '23:59:59',
        'max_votes' => 1
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .back-arrow {
    position: absolute;
    top: 20px;
    left: 20px;
    font-size: 24px;
    text-decoration: none;
    color: #00ffd2;
    border: 2px solid #00ffd2;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
}
.back-arrow:hover {
    background-color: #00ffd2;
    color: white;
}
    </style>
</head>
<body>
<a href="admindashboard.php" class="back-arrow">&larr;</a>
    <div class="container mt-5">
        <h2>Election Settings</h2>

        <!-- Display session message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['msg_type']; ?>">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    unset($_SESSION['msg_type']);
                ?>
            </div>
        <?php endif; ?>

        <form action="update_settings.php" method="POST">
            <div class="form-group">
                <label for="election_name">Election Name:</label>
                <input type="text" class="form-control" id="election_name" name="election_name" value="<?php echo htmlspecialchars($current_settings['election_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($current_settings['start_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="start_time">Start Time:</label>
                <input type="time" class="form-control" id="start_time" name="start_time" value="<?php echo htmlspecialchars($current_settings['start_time']); ?>" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($current_settings['end_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="end_time">End Time:</label>
                <input type="time" class="form-control" id="end_time" name="end_time" value="<?php echo htmlspecialchars($current_settings['end_time']); ?>" required>
            </div>
            <div class="form-group">
                <label for="max_votes">Max Votes per Voter:</label>
                <input type="number" class="form-control" id="max_votes" name="max_votes" value="<?php echo htmlspecialchars($current_settings['max_votes']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</body>
</html>
