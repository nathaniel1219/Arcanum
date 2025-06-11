<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connect_db();
$user_id = $_SESSION['user_id'];
$cartItems = getCartItems($conn, $user_id);
if (empty($cartItems)) {
    header("Location: cart.php");
    exit();
}

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Arcanum</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Tilt+Neon&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800" style="font-family: 'Tilt Neon', sans-serif;">
  <header class="bg-white py-4 px-6 shadow-md">
    <div class="max-w-7xl mx-auto flex justify-center items-center">
      <a href="index.php">
        <img src="/Arcanum/images/ARCANUM.png" alt="Arcanum Logo" class="h-10 object-contain">
      </a>
    </div>
  </header>

  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Checkout</h1>

    <form action="place_order.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
        <input type="text" name="name" placeholder="Full Name" required class="w-full border rounded px-4 py-2 mb-4">
        <input type="text" name="address" placeholder="Address" required class="w-full border rounded px-4 py-2 mb-4">
        <input type="text" name="city" placeholder="City" required class="w-full border rounded px-4 py-2 mb-4">
        <input type="text" name="phone" placeholder="Phone Number" required class="w-full border rounded px-4 py-2 mb-4">
      </div>

      <div>
        <h2 class="text-xl font-semibold mb-4">Payment & Summary</h2>
        <select name="payment_method" class="w-full border rounded px-4 py-2 mb-4" required>
          <option value="">Select Payment Method</option>
          <option value="cod">Cash on Delivery</option>
          <option value="card">Credit/Debit Card</option>
        </select>

        <div class="bg-white p-4 rounded shadow">
          <h3 class="font-bold text-lg mb-2">Order Summary</h3>
          <p class="mb-2">Subtotal: LKR <?= number_format($subtotal, 2) ?></p>
          <p class="mb-2">Shipping: Free</p>
          <p class="font-bold text-[#F4B14E]">Total: LKR <?= number_format($subtotal, 2) ?></p>
        </div>

        <input type="hidden" name="total" value="<?= $subtotal ?>">
        <button type="submit" class="mt-6 w-full bg-[#F4B14E] text-white py-2 rounded hover:bg-yellow-600 transition">Place Order</button>
      </div>
    </form>
  </div>
</body>
</html>
