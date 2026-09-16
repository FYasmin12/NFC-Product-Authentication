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

// Fetch Data from user_verifications
$sql = "SELECT uv.id, uv.product_id, uv.mobile_number, pa.product_code 
        FROM user_verifications uv
        JOIN product_authentication pa ON uv.product_id = pa.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Verifications</title>
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
                    Are you sure you want to delete this verification record?
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
        <h1 class="view-title">User Verification Records</h1>
        <table class="table table-bordered table-striped table-center">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Product Code</th>
                    <th>Mobile Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['product_code']}</td>
                            <td>{$row['mobile_number']}</td>
                            <td>
                                <div class='action-buttons'>
                                    <a href=\"javascript:void(0);\" class=\"btn btn-danger btn-sm\" onclick=\"showDeleteModal({$row['id']})\">Delete</a>
                                </div>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No Records Found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Product Authenticator. All rights reserved.</p>
    </footer>

    <script>
        function showDeleteModal(verificationId) {
            // Set the delete link dynamically
            const deleteLink = `delete_user_verification.php?id=${verificationId}`;
            document.getElementById("confirmDeleteButton").href = deleteLink;

            // Show the modal
            $("#deleteModal").modal("show");
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

<?php
$conn->close();
?>
