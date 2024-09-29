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
    <title>Dashboard Header</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s;
        }

        .navbar {
            background-color: #333; /* Match sidebar background */
            color: #fff;
        }
        .navbar .navbar-brand {
            color: #fff;
        }
        .navbar .navbar-nav .nav-link {
            color: #fff;
            margin-right: 15px;
        }
        .navbar .navbar-nav .nav-link:hover {
            color: #ddd;
        }
        .navbar .navbar-nav .dropdown-menu {
            background-color: #333; /* Match sidebar background */
            border: none;
        }
        .navbar .navbar-nav .dropdown-item {
            color: #fff;
        }
        .navbar .navbar-nav .dropdown-item:hover {
            background-color: #575757; /* Match sidebar hover */
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #333; /* Sidebar background */
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: width 0.3s;
            transform: translateX(-100%);
            visibility: hidden;
            opacity: 0;
        }

        .sidebar.visible {
            transform: translateX(0);
            visibility: visible;
            opacity: 1;
        }

        .sidebar h2 {
            margin-top: 0;
            font-size: 24px;
            display: block;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            text-align: center;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #575757; /* Match sidebar hover and active */
        }

        .toggle-btn {
            background-color: transparent;
            color: #fff; /* Match navbar text color */
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 24px;
            transition: transform 0.3s, color 0.3s;
        }

        .toggle-btn.open {
            transform: translateX(250px);
        }

        .toggle-btn:before {
            content: "☰"; /* Hamburger icon */
        }

        .toggle-btn.open:before {
            content: "×"; /* 'X' icon */
        }

        .toggle-btn:hover {
            color: #ddd; /* Match navbar link hover color */
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .content {
                margin-left: 0;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .toggle-btn {
                display: none;
            }
        }

        .content {
            margin-left: 0; /* No margin when sidebar is hidden */
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap; /* Allows cards to wrap to the next line if there's not enough space */
            justify-content: space-between; /* Distributes space between cards */
            gap: 20px; /* Adds space between cards */
            width: 100%; /* Full width of the viewport */
            transition: margin-left 0.3s;
        }

        .sidebar.visible ~ .main-content .content {
            margin-left: 250px; /* Align with the sidebar width when visible */
            width: calc(100% - 250px); /* Adjust content width for sidebar width */
        }

        /* Card Styles */
        .card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1 1 calc(25% - 20px); /* Four cards per row, adjust for gap */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 200px; /* Ensure cards don’t get too small */
            box-sizing: border-box;
        }

        .card h3 {
            margin: 0 0 10px 0;
        }

        .card a {
            color: #1f6f8b;
            text-decoration: none;
            font-weight: bold;
            margin-top: auto;
        }

        .card.new-users {
            background-color: #17a2b8;
            color: white;
        }

        .card.active-users {
            background-color: #007bff;
            color: white;
        }

        .card.banned-users {
            background-color: #dc3545;
            color: white;
        }

        .card.total-users {
            background-color: #28a745;
            color: white;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        /* Chart Styling */
        #voteChart {
            max-width: 70vw;  /* Chart will be 70% of the viewport width */
            max-height: 60vh; /* Chart will be 60% of the viewport height */
            margin: auto;     /* Center the chart horizontally */
            display: block;   /* Ensure the canvas is a block element */
        }

        /* Overlay card styles */
        .overlay-card {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .overlay-card .card {
            width: 80%;
            max-width: 500px;
            padding: 20px;
            background-color: #fff;
            position: relative;
        }

        .overlay-card .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }
    </style>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const button = document.querySelector('.toggle-btn');
            sidebar.classList.toggle('visible');
            button.classList.toggle('open');
        }

        function showOverlayCard(url) {
            const overlayCard = document.querySelector('.overlay-card');
            const overlayContent = document.querySelector('.overlay-card .card .content');

            // Fetch the content from the URL (add_user.php in this case)
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    overlayContent.innerHTML = data;
                    overlayCard.style.display = 'flex';
                });
        }

        function closeOverlayCard() {
            document.querySelector('.overlay-card').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.nav-link[href="add_user.php"]').addEventListener('click', function(event) {
                event.preventDefault();
                showOverlayCard('add_user.php');
            });
            
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <button class="toggle-btn" onclick="toggleSidebar()"></button>
        <a class="navbar-brand" href="admindashboard.php">GetVoting</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="list_users.php">User lists</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_user.php">Add user</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_candidate.php">Add Candidate</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="sidebar">
        <h2>Sidebar</h2>
        <ul>
            <li><a href="admindashboard.php" data-url="admindashboard.php">Dashboard</a></li>
            <li><a href="settings.php" data-url="settings.php">Settings</a></li>
            <li><a href="adminLogout.php" data-url="adminLogout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="content">
            <div class="card new-users">
                <h3>Registered Voters</h3>
                <a href="list_users.php">View Details</a>
            </div>
            <div class="card active-users">
                <h3>Verified Candidates</h3>
                <a href="list_candidates.php">View Details</a>
            </div>
            <div class="card banned-users">
                <h3>Banned Users</h3>
                <a href="banned_users.php">View Details</a>
            </div>
            <div class="card total-users">
                <h3>Election Stats</h3>
                <a href="result.php">View Details</a>
            </div>

            <?php
            // Database connection
            $conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

            // Fetch candidate names and vote counts
            $query = "SELECT name, voteCount FROM candidate";
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

            $candidates = [];
            $voteCounts = [];
            $totalVotes = 0;

            // Extract data from result
            while ($row = mysqli_fetch_assoc($result)) {
                $candidates[] = $row['name'];
                $voteCounts[] = $row['voteCount'];
                $totalVotes += $row['voteCount'];
            }

            // Calculate vote percentages
            $votePercentages = [];
            foreach ($voteCounts as $count) {
                $votePercentages[] = ($count / $totalVotes) * 100;
            }

            // Encode data for JavaScript
            $candidates_json = json_encode($candidates);
            $percentages_json = json_encode($votePercentages);
            ?>

            <h2 style="text-align:center;">Voting Result</h2>
            <canvas id="voteChart"></canvas>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Data from PHP
                var candidates = <?php echo $candidates_json; ?>;
                var votePercentages = <?php echo $percentages_json; ?>;

                // Create the bar chart
                var ctx = document.getElementById('voteChart').getContext('2d');
                var voteChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: candidates,
                        datasets: [{
                            label: 'Vote Percentage (%)',
                            data: votePercentages,
                            backgroundColor: 'blue',
                            borderColor: 'black',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>

    <!-- Overlay card for Add User -->
    <div class="overlay-card">
        <div class="card">
            <span class="close-btn" onclick="closeOverlayCard()">×</span>
            <div class="content">
                <!-- Content from add_user.php will be loaded here -->
                 
            </div>
        </div>
    </div>
</body>
</html>
