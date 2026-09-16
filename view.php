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

// Handle Insert Request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST["product_name"];
    $product_details = $_POST["product_details"];
    $product_code = $_POST["product_code"];
    $manufacturing_date = $_POST["manufacturing_date"];
    $expiry_date = $_POST["expiry_date"];

    $insert_sql = "INSERT INTO product_authentication (product_name, product_details, product_code, manufacturing_date, expiry_date)
                   VALUES ('$product_name', '$product_details', '$product_code', '$manufacturing_date', '$expiry_date')";

    if ($conn->query($insert_sql) === TRUE) {
        header("Location: view.php"); // Redirect without alert
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fetch Data
$sql = "SELECT * FROM product_authentication";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/viewstyle.css">

    <style>
        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            /* Vertically center */
        }

        .modal-content {
            margin: auto;
            /* Center the modal horizontally */
            border-radius: 10px;
            /* Optional: Rounded corners for better look */
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            /* Space between buttons */
            justify-content: center;
            /* Center align the buttons horizontally */
            align-items: center;
            /* Center align vertically (optional) */
        }
    </style>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this product?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteButton" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container view-container">
        <h1 class="view-title">Product List</h1>
        <table class="table table-bordered table-striped table-center">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Product Details</th>
                    <th>Product Code</th>
                    <th>Manufacturing Date</th>
                    <th style="width: 20%;">Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['product_name']}</td>
                            <td>{$row['product_details']}</td>
                            <td>{$row['product_code']}</td>
                            <td>{$row['manufacturing_date']}</td>
                            <td>{$row['expiry_date']}</td>
                            <td>
                             <div class='action-buttons'>
                                <a href='update.php?id={$row['id']}' class='btn btn-warning btn-sm'>Update</a>
                                <a href=\"javascript:void(0);\" class=\"btn btn-danger btn-sm\" onclick=\"showDeleteModal({$row['id']})\">Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No Records Found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Insert Product Button -->
        <button class="btn btn-primary mt-3" id="toggleInsertForm">Insert Product</button>

        <!-- Insert Form -->
        <div class="insert-form mt-3" id="insertForm" style="display: none;width: 1500px; background-color: #fef7f7">
            <h2 class="insert-title">Add New Product</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                </div>
                <div class="form-group">
                    <label for="product_details">Product Details</label>
                    <textarea class="form-control" id="product_details" name="product_details" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="product_code">Product Code</label>
                    <input type="text" class="form-control" id="product_code" name="product_code" required>
                </div>
                <div class="form-group">
                    <label for="manufacturing_date">Manufacturing Date</label>
                    <input type="date" class="form-control" id="manufacturing_date" name="manufacturing_date" required>
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn btn-success">Add Product</button>
                    <button type="button" class="btn btn-secondary" id="cancelButton">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Product Authenticator. All rights reserved.</p>
    </footer>

    <script>
        // Toggle Insert Form Visibility
        document.getElementById('toggleInsertForm').addEventListener('click', function() {
            const form = document.getElementById('insertForm');
            const tableSection = document.querySelector('.view-container');

            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';

                // Smoothly scroll to the table section
                tableSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            } else {
                form.style.display = 'none';
            }
        });

        function showDeleteModal(productId) {
            // Set the delete link dynamically
            const deleteLink = `delete.php?id=${productId}`;
            document.getElementById("confirmDeleteButton").href = deleteLink;

            // Show the modal
            $("#deleteModal").modal("show");
        }
        //Cancel button
        document.getElementById('cancelButton').addEventListener('click', function() {
            const form = document.getElementById('insertForm');
            form.style.display = 'none';
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
<?php
$conn->close();
?>