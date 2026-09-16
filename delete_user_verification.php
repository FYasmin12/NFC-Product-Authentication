<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

// Database Connection


$servername = "sql208.infinityfree.com"; // MySQL Hostname
$username   = "if0_42930832";           // MySQL Username
$password   = "Fahmida2004";   // Account Password
$dbname     = "if0_42930832_nfc";       // Database Name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete query for the user_verifications table
    $delete_sql = "DELETE FROM user_verifications WHERE id = $id";

    if ($conn->query($delete_sql) === TRUE) {
        // Redirect back to the view_user.php page
        header("Location: view_user.php"); // Redirect without alert
        exit();
    } else {
        echo "<script>alert('Error deleting user verification: " . $conn->error . "'); window.location.href='view_user.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request. No ID provided.'); window.location.href='view_user.php';</script>";
}

// Close Connection
$conn->close();
?>
