<?php
// Database Connection

$servername = "sql212.infinityfree.com"; // MySQL Hostname
$username   = "if0_42930535";           // MySQL Username
$password   = "Fahmida2002";   // Account Password
$dbname     = "if0_42930535_nfc";       // Database Name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete query
    $delete_sql = "DELETE FROM product_authentication WHERE id = $id";

    if ($conn->query($delete_sql) === TRUE) {
        header("Location: view.php"); // Redirect without alert
exit();

    } else {
        echo "<script>alert('Error deleting product: " . $conn->error . "'); window.location.href='view.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request. No ID provided.'); window.location.href='view.php';</script>";
}

// Close Connection
$conn->close();
?>
