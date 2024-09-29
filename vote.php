<?php 
// Check if a session is already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<link href="https://fonts.googleapis.com/css?family=Secular+One" rel="stylesheet"> 

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentVote";

echo "<h1></h1>";
echo "<h2>Please vote for your candidate.</h2>";
echo "<h2>Registered Candidates are:</h2>";

// Create connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to get candidate details (ID, name, image)
$sql = "SELECT id, name, image FROM candidate";
$result = $conn->query($sql);
?>

<form action='voteCaste.php' method='POST'>
    <table class="table">
        <thead>
            <tr>
                <th>Candidate ID</th>
                <th>Candidate Image</th>
                <th>Name</th>
                <th>Select</th>
            </tr>
        </thead>
        <tbody>
<?php    
if ($result->num_rows > 0) {
    // Output data for each candidate
    while($row = $result->fetch_assoc()) {
        // Check if image column is not null
        if (isset($row["image"]) && !empty($row["image"])) {
            $imageFileName = basename($row["image"]);
            $imagePath = 'uploads/' . $imageFileName;
            
            if (file_exists($imagePath)) {
                $imgSrc = $imagePath;
            } else {
                $imgSrc = 'uploads/aa.png'; // Path to a default image if the original is not found
            }
        } else {
            $imgSrc = 'uploads/aa.png'; // Path to a default image if no image is provided
        }
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
        echo "<td><img src='" . htmlspecialchars($imgSrc) . "' alt='Candidate Image' class='candidate-image'></td>";  // Display candidate image
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td><input type='radio' name='chosen_candidate' value='" . htmlspecialchars($row['id']) . "'></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No candidates found</td></tr>";
}

$conn->close();
?>
        </tbody>
    </table>
    <input type="submit" value="Vote Now" class="btn">
</form>

<!-- Styling Section -->
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
        color: #333;
    }
    h1, h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }
    .table {
        width: 100%;
        max-width: 600px;
        margin: 30px auto;
        border-collapse: collapse;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .table th, .table td {
        padding: 15px;
        border: 1px solid #ddd;
        text-align: center;
        color: #333;
    }
    .table th {
        background-color: #f7f7f7;
        font-weight: bold;
    }
    .candidate-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
    input[type="radio"] {
        transform: scale(1.5);
    }
    .btn {
        display: block;
        margin: 20px auto;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }
  
</style>
