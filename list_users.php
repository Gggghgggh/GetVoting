<?php 
session_start();
if(!isset($_SESSION['adminLoggedin'])) { 
   header("location: adminLogin.php"); 
   exit();
}

$conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

// Define results per page
$results_per_page = 10;

// Get search term and current page
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Get total number of results
$count_query = "SELECT COUNT(id) AS total FROM users WHERE name LIKE '%$search%' OR studentId LIKE '%$search%'";
$count_result = mysqli_query($conn, $count_query) or die(mysqli_error($conn));
$count_row = mysqli_fetch_array($count_result);
$total_results = $count_row['total'];

// Determine number of total pages available
$number_of_pages = ceil($total_results / $results_per_page);

// Ensure the current page is within range
if ($page > $number_of_pages) $page = $number_of_pages;
if ($page < 1) $page = 1;

// Calculate starting point for results
$this_page_first_result = ($page - 1) * $results_per_page;

// Retrieve results for the current page
$query = "SELECT id, name, studentId, mobileNumber, voted, status, created_at FROM users WHERE name LIKE '%$search%' OR studentId LIKE '%$search%' LIMIT $this_page_first_result, $results_per_page";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
$totalRows = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Secular+One" rel="stylesheet"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style type="text/css">
        body {
            padding-left: 10%;
            padding-right: 10%;
            text-align: center;
            font-family: "Secular One", serif;
        }
        table {
            text-align: center;
        }
        h1 {
            font-size: 36px;
            font-family: "Secular One", serif;
            color: aqua;
        }
        .btn {
            padding: 5px 15px;
            margin: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-light {
            background-color: #f8f9fa;
            color: #000;
        }
        .pagination-container {
            text-align: center;
        }
        .pagination-container .active {
            background-color: #007bff;
            color: white;
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

    <h2 style='text-align:center;'>User List</h2>
    <h5>Total Number of Users: <?php echo $total_results; ?></h5>

    <a href="add_user.php" class="btn btn-primary mt-3">Add New User</a>
    <br><br>

    <!-- Search Input -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by Name or Student ID" value="<?php echo htmlspecialchars($search); ?>">

    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr> 
                <th>ID</th> 
                <th>Name</th> 
                <th>Student ID</th> 
                <th>Mobile Number</th> 
                <th>Voted</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="userTable">
            <?php
            if($totalRows > 0){
                while($row = mysqli_fetch_array($result)){
                    $statusText = $row['status'] ? 'Active' : 'Inactive';
                    $dateCreated = $row['created_at'] ? $row['created_at'] : 'Unknown';
                    echo "<tr>
                        <td>".htmlspecialchars($row['id'])."</td>
                        <td>".htmlspecialchars($row['name'])."</td>
                        <td>".htmlspecialchars($row['studentId'])."</td>
                        <td>".htmlspecialchars($row['mobileNumber'])."</td>
                        <td>".($row['voted'] ? 'Yes' : 'No')."</td>
                        <td>".$statusText."</td>
                        <td>".$dateCreated."</td>
                        <td>
                            <a href='view_user.php?id=".htmlspecialchars($row['id'])."' class='btn btn-info btn-sm'>View</a>
                            <a href='edit_user.php?id=".htmlspecialchars($row['id'])."' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_user.php?id=".htmlspecialchars($row['id'])."' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>";
                    if($row['status']) {
                        echo "<a href='toggle_status.php?id=".htmlspecialchars($row['id'])."&status=0' class='btn btn-warning btn-sm'>Deactivate</a>";
                    } else {
                        echo "<a href='toggle_status.php?id=".htmlspecialchars($row['id'])."&status=1' class='btn btn-success btn-sm'>Activate</a>";
                    }
                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Display the pagination -->
    <div class="pagination-container">
        <?php
        // Pagination numbers
        for ($i = 1; $i <= $number_of_pages; $i++) {
            $activeClass = $i == $page ? 'btn-primary active' : 'btn-light';
            echo '<a href="?page=' . $i . '&search=' . urlencode($search) . '" class="btn ' . $activeClass . '">' . $i . '</a> ';
        }
        ?>
    </div>

    <a href="add_user.php" class="btn btn-primary mt-3">Back to Admin Dashboard</a>

    <script>
    $(document).ready(function() {
        function loadTable(page, search) {
            $.ajax({
                url: 'list_users.php',
                type: 'GET',
                data: {
                    page: page,
                    search: search
                },
                success: function(data) {
                    var newContent = $(data);
                    $('#userTable').html(newContent.find('#userTable').html());
                    $('.pagination-container').html(newContent.find('.pagination-container').html());
                    $('#searchInput').val(search);
                }
            });
        }

        // Search input change
        $('#searchInput').on('keyup', function() {
            var searchTerm = $(this).val();
            loadTable(1, searchTerm);
        });

        // Pagination button click
        $(document).on('click', '.pagination-container a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1].split('&')[0];
            var search = $('#searchInput').val();
            loadTable(page, search);
        });
    });
    </script>
</body>
</html>
