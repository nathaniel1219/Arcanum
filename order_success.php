<?php
session_start();

if (!isset($_SESSION['order_success'])) {
    header("Location: index.php");
    exit();
}

$order = $_SESSION['order_success'];
unset($_SESSION['order_success']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Successful</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Tilt+Neon&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800" style="font-family: 'Tilt Neon', sans-serif;">
  <header class="bg-white py-4 px-6 shadow-md">
    <div class="max-w-7xl mx-auto flex justify-center items-center">
      <a href="index.php">
        <img src="/Arcanum/images/ARCANUM.png" alt="Arcanum Logo" class="h-10 object-contain">
      </a>
    </div>
  </header>

  <div class="max-w-xl mx-auto p-6 mt-12 bg-white rounded shadow text-center">
    <h1 class="text-3xl font-bold text-green-600 mb-4">Thank you for your order!</h1>
    <p class="text-lg mb-4">Your order ID is <span class="font-semibold">#<?= htmlspecialchars($order['order_id']) ?></span>.</p>

    <div class="text-left bg-gray-50 border rounded p-4 text-sm leading-6">
      <p><strong>Name:</strong> <?= htmlspecialchars($order['name']) ?></p>
      <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
      <p><strong>City:</strong> <?= htmlspecialchars($order['city']) ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
      <p><strong>Payment Method:</strong> <?= $order['payment'] === 'cod' ? 'Cash on Delivery' : 'Credit/Debit Card' ?></p>
      <p><strong>Total:</strong> LKR <?= number_format($order['total'], 2) ?></p>
    </div>

    <p class="mt-4">You will receive a confirmation email shortly.</p>
    <a href="index.php" class="mt-6 inline-block bg-[#F4B14E] text-white py-2 px-4 rounded hover:bg-yellow-600">Back to Home</a>
  </div>
</body>
</html>
