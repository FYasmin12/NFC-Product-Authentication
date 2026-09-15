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

// Fetch Product Data for the Given ID
$id = $_GET['id'];
$sql = "SELECT * FROM product_authentication WHERE id = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

// Handle Update Request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST["product_name"];
    $product_details = $_POST["product_details"];
    $product_code = $_POST["product_code"];
    $manufacturing_date = $_POST["manufacturing_date"];
    $expiry_date = $_POST["expiry_date"];

    $update_sql = "UPDATE product_authentication SET 
                   product_name = '$product_name', 
                   product_details = '$product_details', 
                   product_code = '$product_code', 
                   manufacturing_date = '$manufacturing_date', 
                   expiry_date = '$expiry_date' 
                   WHERE id = $id";

    if ($conn->query($update_sql) === TRUE) {
        header("Location: view.php"); // Redirect without alert
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/updatestyle.css">
</head>

<body>
    <!-- Navbar -->
     <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
            <img src="img/NFC_logo_transparent.png" alt="Logo" width="250" height="65">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- Products Page Link -->
                <li class="nav-item">
                    <a class="nav-link" href="view.php">View Products</a>
                </li>
                <!-- Users Page Link -->
                <li class="nav-item">
                    <a class="nav-link" href="view_user.php">View Users</a>
                </li>
                <!-- Logout Page Link -->
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container update-container">
        <h1 class="update-title">Update Product</h1>
        <form method="POST" class="update-form">
            <div class="form-group">
                <label for="product_name">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $product['product_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="product_details">Product Details</label>
                <textarea class="form-control" id="product_details" name="product_details" rows="3" required><?php echo $product['product_details']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="product_code">Product Code</label>
                <input type="text" class="form-control" id="product_code" name="product_code" value="<?php echo $product['product_code']; ?>" required>
            </div>
            <div class="form-group">
                <label for="manufacturing_date">Manufacturing Date</label>
                <input type="date" class="form-control" id="manufacturing_date" name="manufacturing_date" value="<?php echo $product['manufacturing_date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="expiry_date">Expiry Date</label>
                <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?php echo $product['expiry_date']; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update Product</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Product Authenticator. All rights reserved.</p>
    </footer>
</body>

</html>
