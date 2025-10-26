<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet Request</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 500px;
        }
        h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        input:focus, select:focus {
            border-color: #4a6bff;
            outline: none;
        }
        .amount-info {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .payment-method {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-method:hover {
            border-color: #4a6bff;
        }
        .payment-method input {
            width: auto;
            margin-right: 15px;
        }
        .payment-method label {
            margin-bottom: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .payment-icon {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            object-fit: contain;
        }
        .payment-details {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #eee;
        }
        button {
            background-color: #4a6bff;
            color: white;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 20px;
        }
        button:hover {
            background-color: #3a5bef;
        }
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 30px;
            border-radius: 5px;
            color: white;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            animation: slideIn 0.5s, fadeOut 0.5s 2.5s forwards;
        }
        .alert.success {
            background-color: #4CAF50;
            border-left: 5px solid #388E3C;
        }
        .alert.error {
            background-color: #F44336;
            border-left: 5px solid #D32F2F;
        }
        .alert-icon {
            margin-right: 15px;
            font-size: 24px;
        }
        @keyframes slideIn {
            from { top: -100px; opacity: 0; }
            to { top: 20px; opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; visibility: hidden; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Money to Wallet</h2>
        <form method="POST" action="submit_wallet_request.php" onsubmit="return validateForm()">
            <div class="form-group">
                <input type="number" id="amount" name="amount" placeholder="Amount (BDT)" required>
                <div class="amount-info">Enter amount between BDT<span style="color: red;"> ৳1,000</span> to <span style="color: red;"> ৳50,000</span></div>
            </div>

            <div class="form-group">
                <label>Select Payment Method</label>
                
                <div class="payment-method">
                    <input type="radio" id="bkash" name="payment_method" value="bKash" required>
                    <label for="bkash">
                        <!-- <img src="https://seeklogo.com/images/B/bkash-logo-C7D7981C1F-seeklogo.com.png" alt="bKash" class="payment-icon"> -->
                        bKash
                    </label>
                </div>
                
                <div class="payment-method">
                    <input type="radio" id="nagad" name="payment_method" value="Nagad">
                    <label for="nagad">
                        <!-- <img src="https://seeklogo.com/images/N/nagad-logo-9D211D5D0D-seeklogo.com.png" alt="Nagad" class="payment-icon"> -->
                        Nagad
                    </label>
                </div>
                
                <div class="payment-method">
                    <input type="radio" id="rocket" name="payment_method" value="Rocket">
                    <label for="rocket">
                        <!-- <img src="https://seeklogo.com/images/D/dutch-bangla-rocket-logo-8B8B9B5F0C-seeklogo.com.png" alt="Rocket" class="payment-icon"> -->
                        Rocket
                    </label>
                </div>
            </div>

            <div id="payment-details" class="payment-details">
                <div class="form-group">
                    <label for="mobile_number">Mobile Number</label>
                    <input type="text" id="mobile_number" name="mobile_number" placeholder="01XXXXXXXXX" required>
                </div>
                
                <div class="form-group">
                    <label for="transaction_pin">PIN</label>
                    <input type="password" id="transaction_pin" name="transaction_pin" placeholder="XXXXX" maxlength="5" required>
                </div>
            </div>

            <button type="submit" id="submit-btn">Request Add Money</button>
        </form>
    </div>

    <?php if(isset($message)): ?>
        <div class="alert <?php echo $alertType; ?>">
            <span class="alert-icon"><?php echo $alertType === 'success' ? '✓' : '✗'; ?></span>
            <?php echo $message; ?>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = 'verify_transaction.php';
            }, 2000);
        </script>
    <?php endif; ?>

    <script>
        // Show payment details when a method is selected
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const paymentDetails = document.getElementById('payment-details');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                if(this.checked) {
                    paymentDetails.style.display = 'block';
                }
            });
        });

        function validateForm() {
            const amount = document.getElementById('amount').value;
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            const mobileNumber = document.getElementById('mobile_number').value;
            const transactionPin = document.getElementById('transaction_pin').value;
            
            // Validate amount
            if(amount < 1000 || amount > 50000) {
                alert('Amount must be between ৳1,000 and ৳50,000');
                return false;
            }
            
            // Validate payment method
            if(!paymentMethod) {
                alert('Please select a payment method');
                return false;
            }
            
            // Validate mobile number
            if(!/^01[3-9]\d{8}$/.test(mobileNumber)) {
                alert('Please enter a valid Bangladeshi mobile number');
                return false;
            }
            
            // Validate transaction pin
            if(!/^\d{5}$/.test(transactionPin)) {
                alert('Please enter a valid 5-digit PIN');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>