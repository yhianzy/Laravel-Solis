<?php
require_once 'config/database.php';
require_once 'config/session_helper.php';

// Check if user is logged in as a member
if (!isLoggedIn() || !isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_SESSION['member_id'];
    $service_type = trim($_POST['service_type'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $reference_number = trim($_POST['reference_number'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($service_type) || !in_array($service_type, ['Day Session', 'Monthly Membership', 'Boxing', 'Dancing'])) {
        $errors[] = "Please select a valid service";
    }
    
    if (empty($start_date)) {
        $errors[] = "Please select a date";
    }
    
    if (empty($reference_number)) {
        $errors[] = "Reference number is required";
    }
    
    // Check if file was uploaded
    if (!isset($_FILES['receipt_image']) || $_FILES['receipt_image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Receipt image is required";
    } else {
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $file_type = $_FILES['receipt_image']['type'];
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Invalid file type. Please upload a JPG, PNG, or GIF image.";
        }
        
        // Check file size (max 5MB)
        if ($_FILES['receipt_image']['size'] > 5000000) {
            $errors[] = "File size too large. Maximum size is 5MB.";
        }
    }
    
    if (empty($errors)) {
        $conn = getDBConnection();
        
        // Check if reference number already exists
        $check_ref = $conn->prepare("SELECT payment_id FROM payments WHERE reference_number = ?");
        $check_ref->bind_param("s", $reference_number);
        $check_ref->execute();
        $ref_result = $check_ref->get_result();
        
        if ($ref_result->num_rows > 0) {
            $check_ref->close();
            closeDBConnection($conn);
            header("Location: Payment_Section.php?error=" . urlencode("This reference number has already been used"));
            exit;
        }
        $check_ref->close();
        
        // Calculate end date based on service type
        $start_date_obj = new DateTime($start_date);
        if ($service_type === 'Monthly Membership') {
            // Add 30 days for monthly membership
            $end_date_obj = clone $start_date_obj;
            $end_date_obj->modify('+30 days');
        } else {
            // For Day Session, Boxing, Dancing - same date
            $end_date_obj = clone $start_date_obj;
        }
        
        $start_date_db = $start_date_obj->format('Y-m-d');
        $end_date_db = $end_date_obj->format('Y-m-d');
        
        // Map service type for database (Monthly Membership -> Monthly)
        $db_service_type = ($service_type === 'Monthly Membership') ? 'Monthly' : $service_type;
        
        // Get pricing
        $servicePrices = [
            'Day Session' => 25,
            'Monthly' => 300,
            'Boxing' => 150,
            'Dancing' => 100
        ];
        $amount = $servicePrices[$db_service_type] ?? 0;
        
        // Create uploads directory if it doesn't exist
        $upload_dir = 'uploads/receipts/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
            // Create .htaccess to prevent direct access (optional security)
            file_put_contents($upload_dir . '.htaccess', 'Deny from all');
        }
        
        // Generate unique filename
        $file_extension = pathinfo($_FILES['receipt_image']['name'], PATHINFO_EXTENSION);
        $unique_filename = 'receipt_' . $member_id . '_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $unique_filename;
        
        // Upload file
        if (move_uploaded_file($_FILES['receipt_image']['tmp_name'], $upload_path)) {
            // Use transaction to ensure both membership and payment are created together
            $conn->begin_transaction();
            
            try {
                // Create membership record first
                $membership_stmt = $conn->prepare("INSERT INTO memberships (member_id, membership_type, start_date, end_date, status) VALUES (?, ?, ?, ?, 'Pending')");
                if (!$membership_stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                
                $membership_stmt->bind_param("isss", $member_id, $db_service_type, $start_date_db, $end_date_db);
                
                if (!$membership_stmt->execute()) {
                    throw new Exception("Membership insert failed: " . $membership_stmt->error . " (DB Error: " . $conn->error . ")");
                }
                
                $membership_id = $conn->insert_id;
                
                if (!$membership_id || $membership_id <= 0) {
                    throw new Exception("Failed to get membership ID after insert");
                }
                
                $membership_stmt->close();
                
                // Create payment record
                $payment_stmt = $conn->prepare("INSERT INTO payments (member_id, membership_id, amount, payment_type, reference_number, receipt_image, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
                if (!$payment_stmt) {
                    throw new Exception("Payment prepare failed: " . $conn->error);
                }
                
                $payment_stmt->bind_param("iidsss", $member_id, $membership_id, $amount, $db_service_type, $reference_number, $upload_path);
                
                if (!$payment_stmt->execute()) {
                    throw new Exception("Payment insert failed: " . $payment_stmt->error . " (DB Error: " . $conn->error . ")");
                }
                
                $payment_stmt->close();
                
                // Commit transaction
                $conn->commit();
                closeDBConnection($conn);
                
                // Redirect to success page
                header("Location: Payment_Section.php?success=1&membership_id=" . $membership_id);
                exit;
                
            } catch (Exception $e) {
                // Rollback transaction on any error
                $conn->rollback();
                $error = $e->getMessage();
                
                // Delete uploaded file if transaction failed
                if (file_exists($upload_path)) {
                    unlink($upload_path);
                }
                
                // Close any open statements
                if (isset($membership_stmt) && $membership_stmt) {
                    $membership_stmt->close();
                }
                if (isset($payment_stmt) && $payment_stmt) {
                    $payment_stmt->close();
                }
                
                closeDBConnection($conn);
                header("Location: Payment_Section.php?error=" . urlencode($error));
                exit;
            }
        } else {
            $error = "Failed to upload receipt image. Error: " . $_FILES['receipt_image']['error'];
            closeDBConnection($conn);
            header("Location: Payment_Section.php?error=" . urlencode($error));
            exit;
        }
        
        closeDBConnection($conn);
        header("Location: Payment_Section.php?error=" . urlencode($error ?? "Failed to process payment"));
        exit;
    } else {
        header("Location: Payment_Section.php?error=" . urlencode(implode(", ", $errors)));
        exit;
    }
} else {
    header("Location: Payment_Section.php");
    exit;
}
?>

