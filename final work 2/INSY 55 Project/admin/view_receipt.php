<?php
require_once '../config/database.php';
require_once '../config/session_helper.php';

// Check if user is logged in as admin
if (!isLoggedIn() || getUserType() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$payment_id = $_GET['id'] ?? 0;

if ($payment_id <= 0) {
    die("Invalid payment ID");
}

$conn = getDBConnection();

// Get payment receipt
$stmt = $conn->prepare("SELECT receipt_image, reference_number, payment_type, amount FROM payments WHERE payment_id = ?");
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();
$stmt->close();
closeDBConnection($conn);

if (!$payment || !$payment['receipt_image']) {
    die("Receipt not found");
}

// Check file path - receipt_image is stored relative to project root
$receipt_file_path = trim($payment['receipt_image']);

// Remove leading slash if present
$receipt_file_path = ltrim($receipt_file_path, '/\\');

// Try multiple path variations
$paths_to_check = [
    '../' . $receipt_file_path,  // Relative from admin directory
    __DIR__ . '/../' . $receipt_file_path,  // Absolute path
    $receipt_file_path  // Try as-is (in case it's already correct)
];

$found_path = null;
foreach ($paths_to_check as $path) {
    if (file_exists($path)) {
        $found_path = $path;
        break;
    }
}

if (!$found_path) {
    // Try to show helpful error
    $base_dir = __DIR__ . '/../';
    die("Receipt file not found.<br><br>"
        . "Looking for: " . htmlspecialchars($receipt_file_path) . "<br>"
        . "From directory: " . htmlspecialchars($base_dir) . "<br>"
        . "Tried paths:<br>"
        . "- " . htmlspecialchars('../' . $receipt_file_path) . "<br>"
        . "- " . htmlspecialchars($base_dir . $receipt_file_path) . "<br>"
        . "- " . htmlspecialchars($receipt_file_path) . "<br>"
        . "Please verify the file exists in the uploads/receipts directory.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Receipt</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #1a1a1a;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .receipt-container {
            background: #2c2c2c;
            padding: 30px;
            border-radius: 12px;
            max-width: 800px;
            width: 100%;
        }
        
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .receipt-header h2 {
            margin: 0;
        }
        
        .close-btn {
            padding: 10px 20px;
            background: #666;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .close-btn:hover {
            background: #777;
        }
        
        .receipt-info {
            margin-bottom: 20px;
            padding: 15px;
            background: #3c3c3c;
            border-radius: 8px;
        }
        
        .receipt-info p {
            margin: 8px 0;
        }
        
        .receipt-image {
            text-align: center;
        }
        
        .receipt-image img {
            max-width: 100%;
            height: auto;
            border: 2px solid #555;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="receipt-header">
        <h2>Payment Receipt</h2>
        <button class="close-btn" onclick="window.close()">Close</button>
    </div>
    
    <div class="receipt-info">
        <p><strong>Reference Number:</strong> <?php echo htmlspecialchars($payment['reference_number']); ?></p>
        <p><strong>Service Type:</strong> <?php echo htmlspecialchars($payment['payment_type']); ?></p>
        <p><strong>Amount:</strong> ₱<?php echo number_format($payment['amount'], 2); ?></p>
    </div>
    
    <div class="receipt-image">
        <?php 
        // Build web-accessible path
        // The receipt_image is stored as "uploads/receipts/filename.jpg"
        // From admin directory, we need "../uploads/receipts/filename.jpg"
        $receipt_image = $payment['receipt_image'];
        
        // Remove leading slashes if any
        $receipt_image = ltrim($receipt_image, '/\\');
        
        // Build relative path from admin directory
        $web_path = '../' . $receipt_image;
        
        // Normalize path separators for web (use forward slashes)
        $web_path = str_replace('\\', '/', $web_path);
        ?>
        <img src="<?php echo htmlspecialchars($web_path); ?>" alt="Receipt" onerror="alert('Receipt image could not be loaded.\\nTrying to load: <?php echo htmlspecialchars($web_path); ?>\\nOriginal path: <?php echo htmlspecialchars($payment['receipt_image']); ?>'); this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23fff\'%3EImage not found%3C/text%3E%3C/svg%3E';">
    </div>
</div>

</body>
</html>

