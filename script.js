// JavaScript for handling product verification
function verifyProduct() {
    const productCode = document.getElementById("productCode").value;
    const resultDiv = document.getElementById("result");

    if (!productCode) {
        resultDiv.innerHTML = '<div class="alert alert-danger" role="alert">Please enter a product code!</div>';
        return;
    }

    // Example of AJAX request (replace the URL with your server's API endpoint)
    fetch(`http://your-server-url/verify.php?code=${productCode}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "authentic") {
                resultDiv.innerHTML = `
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Authentic Product!</h4>
                        <p><strong>Name:</strong> ${data.product_name}</p>
                        <p><strong>Manufacture Date:</strong> ${data.manufacture_date}</p>
                        <p><strong>Expiry Date:</strong> ${data.expiry_date}</p>
                        <p><strong>Details:</strong> ${data.product_details}</p>
                    </div>`;
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Fake Product!</h4>
                        <p>This product is not registered in the database.</p>
                    </div>`;
            }
        })
        .catch(error => {
            resultDiv.innerHTML = '<div class="alert alert-danger" role="alert">Error verifying product. Please try again later.</div>';
            console.error('Error:', error);
        });
}

function resetFields() {
    document.getElementById("productCode").value = "";
    document.getElementById("result").innerHTML = ""; // Clear the result section
}

// QR Code Scanning Functions
let html5QrCodeScanner;

function openQrScanner() {
    // Show the scanner interface
    document.getElementById("scanner-interface").style.display = "block";

    // Initialize the QR code scanner
    html5QrCodeScanner = new Html5QrcodeScanner(
        "my-qr-reader",
        {
            fps: 10,
            qrbox: { width: 250, height: 250 },
        }
    );

    html5QrCodeScanner.render(onScanSuccess, onScanError);
}

function onScanSuccess(decodedText) {
    // Populate the input field with the scanned QR code
    const productCodeInput = document.getElementById("productCode");
    productCodeInput.value = decodedText;

    // Hide the scanner interface
    document.getElementById("scanner-interface").style.display = "none";

    // Automatically trigger the verifyProduct function
    verifyProduct();

    // Stop and clear the scanner
    html5QrCodeScanner.clear();
}

function onScanError(errorMessage) {
    console.error(`QR Code Scan Error: ${errorMessage}`);
}

function closeQrScanner() {
    // Hide the scanner interface
    document.getElementById("scanner-interface").style.display = "none";

    // Stop and clear the scanner if initialized
    if (html5QrCodeScanner) {
        html5QrCodeScanner.clear();
    }
}

function restartQrScanner() {
    // Restart the scanner
    openQrScanner();
}
