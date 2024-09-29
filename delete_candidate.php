<?php
session_start();
if (!isset($_SESSION['adminLoggedin'])) {
    header("location: adminLogin.php");
    exit();
}

$id = $_GET['id'] ?? null;
if ($id) {
    $conn = mysqli_connect("localhost", "root", "", "studentVote") or die(mysqli_error($conn));

    $delete_query = "DELETE FROM candidate WHERE id = $id";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['message'] = 'Candidate deleted successfully.';
    } else {
        $_SESSION['message'] = 'Failed to delete candidate.';
    }
    header("location: list_candidates.php");
    exit();
} else {
    die("Invalid candidate ID.");
}
?>
