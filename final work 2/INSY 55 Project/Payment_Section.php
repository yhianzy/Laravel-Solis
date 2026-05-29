<?php 
require_once 'config/session_helper.php';

// Redirect to signup if not logged in
if (!isLoggedIn() || !isset($_SESSION['member_id'])) {
    header("Location: signup.php?redirect=payment");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="CSS/payments.css">
    <style>
        .error-msg {
            background: #e74c3c;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            max-width: 85%;
            margin-left: auto;
            margin-right: auto;
        }
        
        .success-msg {
            background: #2ecc71;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            max-width: 85%;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        
        .success-msg h2 {
            margin-top: 0;
        }
        
        .success-msg a {
            color: white;
            text-decoration: underline;
            font-weight: 600;
        }
        
        .file-name {
            color: #2ecc71;
            margin-top: 10px;
            font-size: 14px;
        }
        
        .date-info {
            color: #aaa;
            font-size: 14px;
            margin-top: 5px;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
        }
        
        .modal-content {
            background: #2d2d2d;
            margin: 10% auto;
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            color: white;
        }
        
        .modal-content h2 {
            margin-top: 0;
        }
        
        .modal-content .info-row {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            padding: 10px 0;
            border-bottom: 1px solid #444;
        }
        
        .modal-content .info-label {
            color: #aaa;
        }
        
        .modal-content .info-value {
            font-weight: 600;
        }
        
        .modal-buttons {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }
        
        .modal-btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            flex: 1;
        }
        
        .modal-btn-cancel {
            background: #666;
            color: white;
        }
        
        .modal-btn-cancel:hover {
            background: #555;
        }
        
        .modal-btn-confirm {
            background: #2ecc71;
            color: white;
        }
        
        .modal-btn-confirm:hover {
            background: #27ae60;
        }
        
        .next-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div class="payment-container">

    <!-- BACK BUTTON -->
    <button class="back-btn" onclick="window.location.href='index.php'">&larr; Back</button>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-msg">
            <h2>Payment Submitted Successfully!</h2>
            <p>Your payment has been submitted and is pending verification by the admin.</p>
            <p>You can check the status of your payment in <a href="member_account.php">Account Management</a>.</p>
            <p style="margin-top: 20px;">
                <button onclick="window.location.href='member_account.php'" style="padding: 12px 24px; background: white; color: #2ecc71; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; margin-right: 10px;">View My Account</button>
                <button onclick="window.location.href='Payment_Section.php'" style="padding: 12px 24px; background: #666; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Submit Another Payment</button>
            </p>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="error-msg">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <!-- TOP SELECTION BAR -->
    <div class="select-row">
        <div class="selector">
            <label>select any of our services:</label>
            <select id="serviceSelect" name="service_type" required>
                <?php 
                $selected_service = $_GET['service'] ?? '';
                $valid_services = ['Day Session', 'Monthly Membership', 'Boxing', 'Dancing'];
                // Validate service to prevent XSS
                if (!in_array($selected_service, $valid_services)) {
                    $selected_service = '';
                }
                ?>
                <option value="Day Session" <?php echo ($selected_service === 'Day Session') ? 'selected' : ''; ?>>Day Session</option>
                <option value="Monthly Membership" <?php echo ($selected_service === 'Monthly Membership') ? 'selected' : ''; ?>>Monthly Membership</option>
                <option value="Boxing" <?php echo ($selected_service === 'Boxing') ? 'selected' : ''; ?>>Boxing</option>
                <option value="Dancing" <?php echo ($selected_service === 'Dancing') ? 'selected' : ''; ?>>Dancing</option>
            </select>
        </div>

        <div class="selector">
            <label>Select start date</label>
            <input type="date" id="datePicker" name="start_date" required min="<?php echo date('Y-m-d'); ?>">
            <div class="date-info" id="dateInfo"></div>
        </div>
    </div>

    <!-- MAIN PAYMENT BOX -->
    <div class="payment-box">

        <!-- LEFT: QR CODE -->
        <div class="qr-box">
            <img src="img/gcash-qr.jpg" alt="QR Code">
        </div>

        <!-- RIGHT SIDE CONTENT -->
        <div class="pay-info">
            <form id="paymentForm" action="process_payment.php" method="POST" enctype="multipart/form-data">
                <h1>PAYMENT</h1>
                <p class="amount" id="amountDisplay">₱25 (Day Session)</p>

                <p class="instructions">
                    Proceed to pay the specified amount by scanning the GCash QR code provided.
                    Make sure to pay the exact amount and save or screenshot the receipt of payment.
                    Type the reference number in the field below and upload the image of your receipt.
                </p>

                <input type="hidden" name="service_type" id="serviceTypeInput">
                <input type="hidden" name="start_date" id="startDateInput">

                <!-- REFERENCE NUMBER INPUT -->
                <label>please input the reference number:</label>
                <input type="text" class="ref-input" id="referenceNumber" name="reference_number" placeholder="Enter reference number" required>

                <!-- UPLOAD RECEIPT -->
                <div class="upload-row">
                    <label>Upload the photo of receipt:</label>

                    <!-- HIDDEN FILE INPUT -->
                    <input type="file" id="receiptUpload" name="receipt_image" class="upload-btn" accept="image/jpeg,image/jpg,image/png,image/gif" required>

                    <!-- CUSTOM BUTTON -->
                    <label for="receiptUpload" class="upload-label">
                        Choose Receipt
                    </label>
                    <div class="file-name" id="fileName"></div>
                </div>

                <!-- NEXT BUTTON -->
                <button type="button" class="next-btn" id="nextBtn" onclick="showConfirmation()">Next</button>
            </form>
        </div>
    </div>

</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <h2>Confirm Payment</h2>
        <p>Please review your payment details:</p>
        
        <div class="info-row">
            <div class="info-label">Service:</div>
            <div class="info-value" id="confirmService"></div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Amount:</div>
            <div class="info-value" id="confirmAmount"></div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Start Date:</div>
            <div class="info-value" id="confirmStartDate"></div>
        </div>
        
        <div class="info-row">
            <div class="info-label">End Date:</div>
            <div class="info-value" id="confirmEndDate"></div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Reference Number:</div>
            <div class="info-value" id="confirmReference"></div>
        </div>
        
        <div class="modal-buttons">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeConfirmation()">Cancel</button>
            <button type="button" class="modal-btn modal-btn-confirm" onclick="submitPayment()">Confirm and Submit</button>
        </div>
    </div>
</div>

<script>
    // Service pricing
    const servicePrices = {
        'Day Session': 25,
        'Monthly Membership': 300,
        'Boxing': 150,
        'Dancing': 100
    };

    // Get elements
    const serviceSelect = document.getElementById('serviceSelect');
    const datePicker = document.getElementById('datePicker');
    const amountDisplay = document.getElementById('amountDisplay');
    const dateInfo = document.getElementById('dateInfo');
    const serviceTypeInput = document.getElementById('serviceTypeInput');
    const referenceNumber = document.getElementById('referenceNumber');
    const receiptUpload = document.getElementById('receiptUpload');
    const fileName = document.getElementById('fileName');
    const nextBtn = document.getElementById('nextBtn');
    const paymentForm = document.getElementById('paymentForm');

    // Function to update amount and service name
    function updatePaymentInfo() {
        const selectedService = serviceSelect.value;
        const price = servicePrices[selectedService];
        
        // Update the amount display
        amountDisplay.textContent = `₱${price} (${selectedService})`;
        
        // Update hidden inputs
        serviceTypeInput.value = selectedService;
        if (datePicker.value) {
            document.getElementById('startDateInput').value = datePicker.value;
        }
        
        // Update date info
        updateDateInfo();
    }
    
    // Function to update date information
    function updateDateInfo() {
        const selectedService = serviceSelect.value;
        const selectedDate = datePicker.value;
        
        if (selectedDate) {
            const startDate = new Date(selectedDate);
            let endDate;
            
            if (selectedService === 'Monthly Membership') {
                endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + 30);
                dateInfo.textContent = `End date will be: ${endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
            } else {
                dateInfo.textContent = `End date will be the same: ${startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
            }
        } else {
            dateInfo.textContent = '';
        }
    }
    
    // Function to validate form
    function validateForm() {
        if (!serviceSelect.value || !datePicker.value || !referenceNumber.value.trim() || !receiptUpload.files.length) {
            return false;
        }
        return true;
    }
    
    // Function to update next button state
    function updateNextButton() {
        if (validateForm()) {
            nextBtn.disabled = false;
        } else {
            nextBtn.disabled = true;
        }
    }

    // Listen for service selection changes
    serviceSelect.addEventListener('change', function() {
        updatePaymentInfo();
        updateNextButton();
    });
    
    // Listen for date changes
    datePicker.addEventListener('change', function() {
        // Update hidden input when date changes
        document.getElementById('startDateInput').value = this.value;
        updateDateInfo();
        updateNextButton();
    });
    
    // Listen for reference number input
    referenceNumber.addEventListener('input', updateNextButton);
    
    // Listen for file selection
    receiptUpload.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileName.textContent = 'Selected: ' + this.files[0].name;
            updateNextButton();
        } else {
            fileName.textContent = '';
            updateNextButton();
        }
    });

    // Initialize on page load
    updatePaymentInfo();
    updateNextButton();
    
    // Show confirmation modal
    function showConfirmation() {
        if (!validateForm()) {
            alert('Please fill in all required fields: service, date, reference number, and receipt image.');
            return;
        }
        
        const selectedService = serviceSelect.value;
        const selectedDate = datePicker.value;
        const refNum = referenceNumber.value.trim();
        
        // Calculate end date
        const startDate = new Date(selectedDate);
        let endDate;
        if (selectedService === 'Monthly Membership') {
            endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + 30);
        } else {
            endDate = new Date(startDate);
        }
        
        // Format dates
        const formatDate = (date) => {
            return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
        };
        
        // Update confirmation modal
        document.getElementById('confirmService').textContent = selectedService;
        document.getElementById('confirmAmount').textContent = '₱' + servicePrices[selectedService];
        document.getElementById('confirmStartDate').textContent = formatDate(startDate);
        document.getElementById('confirmEndDate').textContent = formatDate(endDate);
        document.getElementById('confirmReference').textContent = refNum;
        
        // Show modal
        document.getElementById('confirmationModal').style.display = 'block';
    }
    
    // Close confirmation modal
    function closeConfirmation() {
        document.getElementById('confirmationModal').style.display = 'none';
    }
    
    // Submit payment
    function submitPayment() {
        // Ensure all form values are set before submitting
        document.getElementById('serviceTypeInput').value = serviceSelect.value;
        document.getElementById('startDateInput').value = datePicker.value;
        paymentForm.submit();
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('confirmationModal');
        if (event.target == modal) {
            closeConfirmation();
        }
    }
</script>

</body>
</html>
