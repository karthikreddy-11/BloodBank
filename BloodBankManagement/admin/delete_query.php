<?php
include 'conn.php'; // Database connection file
include 'session.php'; // Session management file

if (isset($_GET['id'])) {
    $que_id = $_GET['id'];
    $sql = "DELETE FROM contact_query WHERE query_id={$que_id}";

    if (mysqli_query($conn, $sql)) {
        // Redirect to query.php after deletion
        header("Location: query.php");
        exit();
    } else {
        echo '<div class="alert alert-danger">Error deleting query: ' . mysqli_error($conn) . '</div>';
    }
} else {
    // If no ID is provided, redirect back to query.php
    header("Location: query.php");
    exit();
}
?>
