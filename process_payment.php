<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $errors = [];

  // Validate payment details
  $name = $_POST["name"];
  $address = $_POST["address"];
  $email = $_POST["email"];
  $amount = $_POST["amount"];
  $cardNumber = $_POST["cardNumber"];
  $expiryDate = $_POST["expiryDate"];
  $cvv = $_POST["cvv"];

  // Validate name: should only contain letters and spaces
  if (!preg_match('/^[a-zA-Z ]+$/', $name)) {
    $errors[] = "Name should only contain letters and spaces";
  }

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address";
  }

  // Validate card number: should be 16 digits
  if (!preg_match('/^\d{16}$/', $cardNumber)) {
    $errors[] = "Invalid card number";
  }

  // Validate CVV: should be 3 digits
  if (!preg_match('/^\d{3}$/', $cvv)) {
    $errors[] = "Invalid CVV";
  }

  // If there are errors, display them using JavaScript
  if (!empty($errors)) {
    echo "<script>alert('" . implode("\\n", $errors) . "'); window.location.href = 'index.php';</script>";
    exit;
  }

  // Payment details are valid, proceed with payment processing
  echo "<script>alert('Payment successful!'); window.location.href = 'index.php';</script>";
}
?>
