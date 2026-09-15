<?php
// Database Connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "nfc";

// Create Connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check Connection
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
