<?php
$conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

$search = mysqli_real_escape_string($conn, $_POST['search']);
$query = "SELECT id, name, studentId, mobileNumber, voted, status, created_at FROM users WHERE name LIKE '%$search%' OR studentId LIKE '%$search%'";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_array($result)){
        $statusText = $row['status'] ? 'Active' : 'Inactive';
        $dateCreated = $row['created_at'] ? $row['created_at'] : 'Unknown';
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['studentId']}</td>
            <td>{$row['mobileNumber']}</td>
            <td>" . ($row['voted'] ? 'Yes' : 'No') . "</td>
            <td>{$statusText}</td>
            <td>{$dateCreated}</td>
            <td>
                <a href='view_user.php?id={$row['id']}' class='btn btn-info btn-sm'>View</a>
                <a href='edit_user.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                <a href='delete_user.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                " . ($row['status'] ? "<a href='toggle_status.php?id={$row['id']}&status=0' class='btn btn-warning btn-sm'>Deactivate</a>" : "<a href='toggle_status.php?id={$row['id']}&status=1' class='btn btn-success btn-sm'>Activate</a>") . "
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='8'>No users found</td></tr>";
}
?>
