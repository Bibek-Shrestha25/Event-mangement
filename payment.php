<?php
session_start(); // Start the session

// Check if $_SESSION['system']['about_content'] is set before echoing it
$about_content = isset($_SESSION['system']['about_content']) ? $_SESSION['system']['about_content'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>
    <!-- About Section -->
   

    <!-- Payment Popup Section -->
    <button id="paymentBtn">Make a Payment</button>
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <!-- This is where your PHP file content will be loaded -->
    </div>

    <script>
        document.getElementById("paymentBtn").addEventListener("click", function() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("popup").style.display = "block";

            // Load PHP file content into the popup
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("popup").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "payment_form.php", true);
            xmlhttp.send();
        });

        document.getElementById("overlay").addEventListener("click", function() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("popup").style.display = "none";
        });
    </script>
</body>
</html>
