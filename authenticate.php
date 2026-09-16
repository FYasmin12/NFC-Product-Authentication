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

// Fetch data from the POST request
$product_code = $_POST['product_code'];
$mobile_number = $_POST['mobile_number'];

// Check if the product exists in the database
$sql = "SELECT * FROM product_authentication WHERE product_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $product_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $product_id = $product['id'];
    $product_name = $product['product_name'];
    $product_details = $product['product_details'];
    $expiry_date = $product['expiry_date'];
    $manufacturing_date = $product['manufacturing_date']; // Fetch manufacturing date
    $current_date = date('Y-m-d'); // Get today's date

    // **Step 1: Check if the product is expired**
    $is_expired = ($expiry_date < $current_date);

    // **Step 2: Check if the product is already verified**
    $sql = "SELECT * FROM user_verifications WHERE product_id = ? AND mobile_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $product_id, $mobile_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $verification = $result->fetch_assoc();

    if ($verification) {
        // If the user has already verified this product
        header("Location: index.php?status=verified_before&verified_at=" . urlencode($verification['verified_at']) . "&details=" . urlencode($product_details) . "&manufacturing_date=" . urlencode($manufacturing_date) . "&expiry_date=" . urlencode($expiry_date));
        exit();
    }

    // **Step 3: Check if the product is verified by a different user**
    $check_other_sql = "SELECT * FROM user_verifications WHERE product_id = ?";
    $stmt = $conn->prepare($check_other_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $other_result = $stmt->get_result();

    if ($other_result->num_rows > 0) {
        // Another user has verified this product
        header("Location: index.php?status=failure&message=used_by_another");
        exit();
    }

    // **Step 4: Insert the new verification if no previous verifications**
    if ($is_expired) {
        // Product is authentic but expired
        header("Location: index.php?status=verified_new_expired&details=" . urlencode($product_details) . "&manufacturing_date=" . urlencode($manufacturing_date) . "&expiry_date=" . urlencode($expiry_date));
        exit();
    } else {
        // Product is authentic and not expired
        $insert_sql = "INSERT INTO user_verifications (product_id, mobile_number) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("is", $product_id, $mobile_number);
        if ($stmt->execute()) {
            // Verification successful
            header("Location: index.php?status=verified_new&details=" . urlencode($product_details) . "&manufacturing_date=" . urlencode($manufacturing_date) . "&expiry_date=" . urlencode($expiry_date));
            exit();
        } else {
            // Redirect with error if insertion fails
            header("Location: index.php?status=failure&message=" . urlencode("Database Error: " . $conn->error));
            exit();
        }
    }

} else {
    // Product does not exist in the database
    header("Location: index.php?status=failure&message=not_found");
    exit();
}

$conn->close();
?>
