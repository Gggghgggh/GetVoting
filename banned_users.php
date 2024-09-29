<?php 
session_start();
if(!isset($_SESSION['adminLoggedin'])) 
{ 
   header("location: adminLogin.php"); 
   exit();
}
?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Secular+One" rel="stylesheet"> 
</head>
<!-- Back arrow button in the upper left -->
<a href="admindashboard.php" class="back-arrow">&larr;</a>

<h1></h1>

<?php 
$conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

// Query to select only users with status = 0 (banned)
$query = "SELECT id, name, studentId, mobileNumber, voted, created_at FROM users WHERE status = 0";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
$totalRows = mysqli_num_rows($result);

echo "<h2 style='text-align:center;'>Banned User List</h2>";
echo "<h5>Total Number of Banned Users: ".$totalRows."</h5>";
?>

<table class="table table-striped table-bordered table-hover">
    <tr> 
        <th>ID</th> 
        <th>Name</th> 
        <th>Student ID</th> 
        <th>Mobile Number</th> 
        <th>Voted</th>
        <th>Date Created</th>
        <th>Action</th>
    </tr>

<?php
if($totalRows > 0){
    while($row = mysqli_fetch_array($result)){
        $dateCreated = $row['created_at'] ? $row['created_at'] : 'Unknown';
?>
    <tr>
        <td><?php echo $row['id'];?> </td>
        <td><?php echo $row['name'];?> </td>
        <td><?php echo $row['studentId'];?> </td>
        <td><?php echo $row['mobileNumber'];?> </td>
        <td><?php echo $row['voted'] ? 'Yes' : 'No'; ?> </td>
        <td><?php echo $dateCreated; ?> </td>
        <td>
            <!-- Option to activate the account -->
            <a href="toggle_status.php?id=<?php echo $row['id'];?>&status=1" class="btn btn-success btn-sm">Activate</a>
        </td>
    </tr>
<?php
    }
} else {
    echo "<tr><td colspan='7'>No banned users found</td></tr>";
}
?>
</table>
<a href="list_users.php" class="btn btn-primary mt-3">Back to User List</a>
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
        background-color: #00ffd2;
        margin: 5px;
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
