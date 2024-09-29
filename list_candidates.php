<?php 
session_start();
if(!isset($_SESSION['adminLoggedin'])) {
    header("location: adminLogin.php"); 
    exit();
}
?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Secular+One" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- Back arrow button in the upper left -->
    <a href="admindashboard.php" class="back-arrow">&larr;</a>

    <h1></h1>

    <?php 
    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

    // Check if a toggle status request has been made
    if(isset($_GET['id']) && isset($_GET['status'])){
        $id = $_GET['id'];
        $status = $_GET['status'];
        $update_query = "UPDATE candidate SET status = '$status' WHERE id = '$id'";
        mysqli_query($conn, $update_query) or die(mysqli_error($conn));
        header("location: ".$_SERVER['PHP_SELF']); // Reload the page to reflect changes
        exit();
    }

    // Define results per page
    $results_per_page = 10;

    // Get search term and current page
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Query to get total number of results
    $count_query = "SELECT COUNT(id) AS total FROM candidate WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
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
    $query = "SELECT id, name, email, contact, voteCount, status FROM candidate WHERE name LIKE '%$search%' OR email LIKE '%$search%' LIMIT $this_page_first_result, $results_per_page";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $totalRows = mysqli_num_rows($result);

    echo "<h2 style='text-align:center;'>Candidate List</h2>";
    echo "<h5>Total Number of Candidates: ".$total_results."</h5>";
    ?>

    <!-- Search Input -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search candidate by Name or Email" value="<?php echo htmlspecialchars($search); ?>">

    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr> 
                <th>ID</th> 
                <th>Name</th> 
                <th>Email</th> 
                <th>Contact</th> 
                <th>Vote Count</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="candidateTable">
            <?php
            if($totalRows > 0){
                while($row = mysqli_fetch_array($result)){
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']);?> </td>
                    <td><?php echo htmlspecialchars($row['name']);?> </td>
                    <td><?php echo htmlspecialchars($row['email']);?> </td>
                    <td><?php echo htmlspecialchars($row['contact']);?> </td>
                    <td><?php echo htmlspecialchars($row['voteCount']);?> </td>
                    <td><?php echo $row['status'] ? 'Active' : 'Inactive'; ?> </td>
                    <td>
                        <a href="view_candidate.php?id=<?php echo htmlspecialchars($row['id']);?>" class="btn btn-info btn-sm">View</a>
                        <a href="edit_candidate.php?id=<?php echo htmlspecialchars($row['id']);?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_candidate.php?id=<?php echo htmlspecialchars($row['id']);?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this candidate?');">Delete</a>
                        <?php if($row['status']) { ?>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo htmlspecialchars($row['id']);?>&status=0" class="btn btn-warning btn-sm">Deactivate</a>
                        <?php } else { ?>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo htmlspecialchars($row['id']);?>&status=1" class="btn btn-success btn-sm">Activate</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='7'>No candidates found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Display the pagination -->
    <div style="text-align:center;">
    <?php
    // Previous button
    if($page > 1){
        echo '<a href="list_candidates.php?page=' . ($page-1) . '&search=' . urlencode($search) . '" class="btn btn-light">Previous</a> ';
    }

    // Pagination numbers
    for ($i = 1; $i <= $number_of_pages; $i++) {
        $activeClass = $i == $page ? 'btn-primary' : 'btn-light';
        echo '<a href="list_candidates.php?page=' . $i . '&search=' . urlencode($search) . '" class="btn ' . $activeClass . '">' . $i . '</a> ';
    }

    // Next button
    if($page < $number_of_pages){
        echo '<a href="list_candidates.php?page=' . ($page+1) . '&search=' . urlencode($search) . '" class="btn btn-light">Next</a>';
    }
    ?>
    </div>

    <a href="admindashboard.php" class="btn btn-primary mt-3">Back to Admin Dashboard</a>

    <style type="text/css">
        body{
            padding-left: 10%;
            padding-right: 10%;
            text-align: center;
            font-family: "Secular One", serif;
        }
        table{
            text-align: center;
        }
        h1 {
           font-size: 36px;
           font-family: "Secular One", serif;
           color: aqua;
        }
        .btn{
            padding:5px 15px;
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

    <script>
    $(document).ready(function() {
        // Keep cursor in search bar and perform live search without refreshing the page
        $('#searchInput').on('keyup', function() {
            var searchTerm = $(this).val();
            $.ajax({
                url: 'list_candidates.php',
                type: 'GET',
                data: {search: searchTerm, page: 1},
                success: function(data) {
                    var newTableBody = $(data).find('#candidateTable').html();
                    $('#candidateTable').html(newTableBody);
                }
            });
        });
    });
    </script>
</body>
