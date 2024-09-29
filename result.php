<?php 
session_start();
if(!isset($_SESSION['adminLoggedin'])) { 
   header("location: adminLogin.php"); 
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Participation & Voting Results</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Secular+One" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            padding-left: 10%;
            padding-right: 10%;
            text-align: center;
            font-family: "Secular One", serif;
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
        table {
            text-align: center;
            margin-top: 0; /* Remove space before the table */
        }
        /* Style the circular container */
        .circular-container {
            width: 300px;
            height: 300px;
            border: 2px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0 auto;
            margin-bottom: 20px; /* Add one-line space after the pie chart */
        }
        /* Make the canvas fit within the circular container */
        #voterPieChart {
            width: 100%;
            height: 100%;
        }
        /* Back button style */
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
    <!-- Back arrow button in the upper left -->
    <a href="admindashboard.php" class="back-arrow">&larr;</a>

    <h1></h1>
    <h2>Voter Participation</h2>
    <!-- Pie chart positioned below the title with spacing after -->
    <div class="circular-container">
        <canvas id="voterPieChart"></canvas>
    </div>

    <?php 
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

    // Fetch total voters from the users table
    $totalVotersQuery = "SELECT COUNT(*) as total FROM users";
    $totalVotersResult = mysqli_query($conn, $totalVotersQuery);
    $totalVotersRow = mysqli_fetch_assoc($totalVotersResult);
    $totalVoters = $totalVotersRow['total'];

    // Fetch voters who have voted
    $votedQuery = "SELECT COUNT(*) as voted FROM users WHERE voted = 1";
    $votedResult = mysqli_query($conn, $votedQuery);
    $votedRow = mysqli_fetch_assoc($votedResult);
    $voted = $votedRow['voted'];

    // Calculate voters who haven't voted
    $notVoted = $totalVoters - $voted;

    // Encode data for JavaScript
    $voterData = json_encode([$voted, $notVoted]);
    $voterLabels = json_encode(["Voted", "Not Voted"]);

    // Fetch candidate data for voting results
    $query = "SELECT id, name, email, voteCount FROM candidate";
    $result = mysqli_query($conn, $query);
    $totalRows = mysqli_num_rows($result);

    echo "<h2 style='text-align:center;'>Voting Results</h2>";
    echo "<h5>Total Number of candidates: ".$totalRows."</h5>";

    // Finding total casted vote
    $sum2 = 0;
    while ($row2 = mysqli_fetch_assoc($result)){
        $sum2 += $row2['voteCount'];
    }

    echo "<h5>Total Number of casted votes till Now: <b>$sum2</b></h5>";

    // Set the pointer back to the beginning
    mysqli_data_seek($result, 0);
    ?>

    <table class="table table-striped table-bordered table-hover">
        <tr> <th>ID</th> <th>Name</th> <th>Email</th> <th>Total Votes (casted)</th> <th>Vote Percentage</th></tr>
        <?php
        if($totalRows > 0) {
            while($row = mysqli_fetch_array($result)) {
                $votePercentage = round(($row['voteCount'] * 100) / $sum2, 2);
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><b><?php echo $row['voteCount']; ?></b></td>
            <td><?php echo $votePercentage." %"; ?></td>
        </tr>
        <?php
            }
        } else {
            echo "No results. No registered candidate.";
        }
        ?>
    </table>

    <!-- Back button below the table -->
    <a href="admindashboard.php" class="btn btn-primary mt-3">Back to Admin Dashboard</a>

    <script>
        var voterData = <?php echo $voterData; ?>;
        var voterLabels = <?php echo $voterLabels; ?>;

        var ctx = document.getElementById('voterPieChart').getContext('2d');
        var voterPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: voterLabels,
                datasets: [{
                    data: voterData,
                    backgroundColor: ['green', 'red'],
                    borderColor: 'black',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                var label = voterLabels[tooltipItem.dataIndex];
                                var value = voterData[tooltipItem.dataIndex];
                                return label + ': ' + value;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
