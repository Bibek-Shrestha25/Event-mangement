<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link href="styles.css" type="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            
        }

        .formm {
            max-width: 1000px;
            margin: auto;
            padding: 10px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            
        }

        h2 {
            text-align: center;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 2px;
            margin: 2px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="formm">
        <h2>Payment Form</h2>
        <form id="paymentForm" action="process_payment.php" method="post" onsubmit="return validateForm()">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <div id="nameError" class="error"></div>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <div id="emailError" class="error"></div>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <div id="addressError" class="error"></div>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" min="0.01" step="0.01" required>
            <div id="amountError" class="error"></div>

            <label for="cardNumber">Card Number:</label>
            <input type="text" id="cardNumber" name="cardNumber" required>
            <div id="cardNumberError" class="error"></div>

            <label for="expiryDate">Expiration Date:</label>
            <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YYYY" required>
            <div id="expiryDateError" class="error"></div>

            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" required>
            <div id="cvvError" class="error"></div>

            <input type="submit" value="Submit Payment">
        </form>
    </div>

    <script>
        function validateForm() {
            var name = document.getElementById('name').value.trim();
            var email = document.getElementById('email').value.trim();
            var address = document.getElementById('address').value.trim();
            var amount = document.getElementById('amount').value.trim();
            var cardNumber = document.getElementById('cardNumber').value.trim();
            var expiryDate = document.getElementById('expiryDate').value.trim();
            var cvv = document.getElementById('cvv').value.trim();
            var isValid = true;

            document.querySelectorAll('.error').forEach(function(element) {
                element.textContent = '';
            });

            if (name === '') {
                document.getElementById('nameError').textContent = 'Name is required';
                isValid = false;
            }

            if (email === '') {
                document.getElementById('emailError').textContent = 'Email is required';
                isValid = false;
            } else if (!isValidEmail(email)) {
                document.getElementById('emailError').textContent = 'Invalid email format';
                isValid = false;
            }

            if (address === '') {
                document.getElementById('addressError').textContent = 'Address is required';
                isValid = false;
            }

            if (amount === '') {
                document.getElementById('amountError').textContent = 'Amount is required';
                isValid = false;
            } else if (parseFloat(amount) <= 0) {
                document.getElementById('amountError').textContent = 'Amount must be greater than 0';
                isValid = false;
            }

            if (cardNumber === '') {
                document.getElementById('cardNumberError').textContent = 'Card number is required';
                isValid = false;
            }

            if (expiryDate === '') {
                document.getElementById('expiryDateError').textContent = 'Expiration date is required';
                isValid = false;
            }

            if (cvv === '') {
                document.getElementById('cvvError').textContent = 'CVV is required';
                isValid = false;
            }

            return isValid;
        }

        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
</body>
</html>
