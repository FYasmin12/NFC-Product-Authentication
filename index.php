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

// Get messages from query parameters
$status = isset($_GET["status"]) ? $_GET["status"] : null;
$product_name = isset($_GET["name"]) ? urldecode($_GET["name"]) : "";
$product_details = isset($_GET["details"]) ? urldecode($_GET["details"]) : "";
$product_code = isset($_GET["code"]) ? urldecode($_GET["code"]) : "";

// Fetch product details to check if expired
if ($product_code) {
    $sql = "SELECT * FROM product_authentication WHERE product_code = '$product_code'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product_id = $product['id'];
        $product_name = $product['product_name'];
        $product_details = $product['product_details'];
        $expiry_date = $product['expiry_date'];
        $current_date = date('Y-m-d'); // Get today's date

        // Check if the product has expired
        $is_expired = ($expiry_date < $current_date);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Authentication</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Alert Box Styling */
        .alert {
            font-weight: bold;
            font-size: 1.2rem;
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #ff0000;
            color: #ffffff;
            font-weight: bold;
            font-size: 1.2rem;
            border: 2px solid #d82c3d;
        }

        .verification-text {
            animation: slideInLeftCentered 0.8s ease-out forwards;
            font-weight: bold;
            text-align: center;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        @keyframes slideInLeftCentered {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }

            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div>
            <a class="navbar-brand gap-3" href="#">
                <img src="img/NFC_logo_transparent.png" alt="Logo" width="250" height="65" class="d-inline-block align-text-top">
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="how-it-works.php">How It Works</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="auth-container">
        <!-- Display Result -->
        <?php if ($status): ?>
            <div class="mt-4 alert <?= $status === 'verified_new' || $status === 'verified_before' || $status === 'verified_new_expired' ? 'alert-success' : 'alert-danger' ?> verification-text">
                <?php if ($status === 'verified_new'): ?>
                    <h5>Product Verified Successfully! This Product is Authentic.</h5>
                    <p><strong>Product Details:</strong> <?= htmlspecialchars($_GET['details']) ?></p>
                    <p><strong>Manufacturing Date:</strong> <?= htmlspecialchars($_GET['manufacturing_date']) ?></p>
                    <p><strong>Expiry Date:</strong> <?= htmlspecialchars($_GET['expiry_date']) ?></p>
                <?php elseif ($status === 'verified_before'): ?>
                    <h5>You have verified this product before on <?= htmlspecialchars($_GET['verified_at']) ?>. And again the product is authentic.</h5>
                    <p><strong>Product Details:</strong> <?= htmlspecialchars($_GET['details']) ?></p>
                    <p><strong>Manufacturing Date:</strong> <?= htmlspecialchars($_GET['manufacturing_date']) ?></p>
                    <p><strong>Expiry Date:</strong> <?= htmlspecialchars($_GET['expiry_date']) ?></p>
                <?php elseif ($status === 'verified_new_expired'): ?>
                    <h5>Product Verified! But this product has expired.</h5>
                    <p><strong>Product Details:</strong> <?= htmlspecialchars($_GET['details']) ?></p>
                    <p><strong>Manufacturing Date:</strong> <?= htmlspecialchars($_GET['manufacturing_date']) ?></p>
                    <p><strong>Expiry Date:</strong> <?= htmlspecialchars($_GET['expiry_date']) ?></p>
                <?php elseif (isset($_GET["message"]) && $_GET["message"] === "used_by_another"): ?>
                    <h5>Warning!!!</h5>
                    <p>This code has already been used by another phone number. Please use the same phone number that used the first time to verify the code. If it is the first time you are verifying the code, then it is likely to be a fake product. Please call us @01622736644.</p>
                <?php else: ?>
                    <h5>This product is not authentic!</h5>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Display expired product message -->
        <?php if (isset($is_expired) && $is_expired): ?>
            <div class="alert alert-danger">
                <h5>Alert!!!</h5>
                <p>This product has expired. Please check the product expiry date for safety.</p>
            </div>
        <?php endif; ?>

        <h1 class="auth-title">Product Authentication</h1>
        <p class="product-code-label">Product Code & Mobile number</p>

        <form action="authenticate.php" method="POST">
            <div class="input-group mb-3" style="max-width: 400px; margin: 0 auto;">
                <input type="password" id="productCode" name="product_code" class="form-control text-center" maxlength="14" placeholder="Touch Product's NFC" required>
            </div>
            <div class="input-group mb-3" style="max-width: 400px; margin: 0 auto;">
                <input type="text" id="mobileNumber" name="mobile_number" class="form-control text-center" maxlength="11" placeholder="Enter Mobile Number" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn btn-verify">VERIFY</button>
                <button type="reset" class="btn btn-reset">RESET</button>
            </div>
        </form>
    </div>


    <div class="qr-option">
        <p>Or scan the product's QR code:</p>
        <!-- Trigger for QR Scanner -->
        <a href="javascript:void(0);" onclick="openQrScanner()">Scan QR Code</a>

        <!-- Scanner Interface -->
        <div id="scanner-interface" style="display: none; margin-top: 20px;">
            <div id="my-qr-reader">
                <!-- Animated Scanner Line -->
                <div class="scanner-line"></div>
            </div>
            <button class="btn btn-danger mt-3" onclick="closeQrScanner()">Cancel</button>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Product Authenticator. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="script.js"></script>

    <script>
        // Clear query parameters from the URL after the page loads
        window.onload = function() {
            if (window.location.href.includes("status=")) {
                const url = new URL(window.location.href);
                url.search = ''; // Clear the query parameters
                window.history.replaceState({}, document.title, url.href); // Update the URL without refreshing the page
            }
        };

        // ESP32 WebSocket IP
        const webSocket = new WebSocket('ws://192.168.181.154:81');


        webSocket.onopen = () => console.log('WebSocket connected');

        //new code add
        webSocket.onmessage = (event) => {
            const uid = event.data; // Data received from the WebSocket
            console.log('UID Received:', uid); // Debug log to verify data

            // Find the input field for the Product Code
            const productCodeInput = document.getElementById('productCode');
            if (productCodeInput) {
                productCodeInput.value = uid; // Insert UID into the input field
            } else {
                console.error('Product Code input field not found!');
            }
        };

        webSocket.onclose = () => console.log('WebSocket disconnected');
        webSocket.onerror = (error) => console.error('WebSocket error:', error);
    </script>
</body>

</html>