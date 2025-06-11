<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$conn = connect_db();
$user_id = $_SESSION['user_id'];
$cart_id = getOrCreateCart($conn, $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantities'])) {
        $updates = [];
        foreach ($_POST['quantity'] as $product_id => $qty) {
            $updates[$product_id] = max(1, (int)$qty);
        }
        updateCartQuantities($conn, $cart_id, $updates);
    } elseif (isset($_POST['delete_selected']) && !empty($_POST['delete'])) {
        $product_ids = array_map('intval', $_POST['delete']);
        removeCartItems($conn, $cart_id, $product_ids);
    }
}

$cartItems = getCartItems($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart - Arcanum</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function toggleSelectAll(checked) {
      document.querySelectorAll('input[name="delete[]"]').forEach(cb => cb.checked = checked);
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Tilt+Neon&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen font-sans text-gray-800 bg-gray-50" style="font-family: 'Tilt Neon', sans-serif;">

  <header class="bg-white py-4 px-6 shadow-sm">
    <div class="flex justify-between items-center max-w-7xl mx-auto">
      <div class="w-1/3"></div>
      <div class="w-1/3 flex justify-center">
        <img src="/Arcanum/images/ARCANUM.png" alt="Arcanum Logo" class="h-10 object-contain" />
      </div>
      <div class="w-1/3 flex justify-end items-center gap-4">
        <a href="account.php" class="text-gray-700 font-medium hover:text-[#F4B14E] transition"><img src="images/account2.svg" alt=""></a>
        <a href="cart.php" class="relative text-gray-700 font-medium hover:text-[#F4B14E] transition"><img src="images/cart2.svg" alt=""></a>
      </div>
    </div>
  </header>

  <div class="max-w-7xl mx-auto px-6 mt-4">
    <a href="index.php" class="inline-block text-sm text-[#F4B14E] hover:underline">
      ← Back to Home
    </a>
  </div>

  <main class="max-w-7xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
    <form method="POST" class="lg:col-span-2">
      <h2 class="text-2xl font-bold mb-6">Your Shopping Cart</h2>

      <div class="flex items-center mb-4">
        <input type="checkbox" id="select-all" class="mr-2" onclick="toggleSelectAll(this.checked)">
        <label for="select-all" class="text-sm text-gray-700">Select All</label>
      </div>

      <?php if (empty($cartItems)): ?>
        <p class="text-gray-600">Your cart is empty.</p>
      <?php else: ?>
        <div class="space-y-4">
          <?php foreach ($cartItems as $item): ?>
            <div class="flex justify-between items-center border p-4 rounded shadow-sm bg-white">
              <div class="flex items-center gap-4">
                <input type="checkbox" name="delete[]" value="<?= $item['product_id'] ?>" class="checkbox-item">
                <img src="/Arcanum/images/products/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="w-16 h-16 object-cover rounded">
                <div>
                  <p class="font-semibold"><?= htmlspecialchars($item['product_name']) ?></p>
                  <p class="text-sm text-gray-600">LKR<?= number_format($item['price'], 2) ?></p>
                </div>
              </div>
              <div class="flex flex-col items-end">
                <input type="number" name="quantity[<?= $item['product_id'] ?>]" value="<?= $item['quantity'] ?>" min="1" class="w-20 border rounded px-2 py-1 text-center mb-2">
                <p class="text-gray-700 font-semibold">LKR<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-6 flex justify-between">
          <button type="submit" name="delete_selected" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete Selected</button>
          <button type="submit" name="update_quantities" class="bg-[#F4B14E] text-white px-4 py-2 rounded hover:bg-yellow-600">Update Quantities</button>
        </div>
      <?php endif; ?>
    </form>

    <?php if (!empty($cartItems)): ?>
      <aside class="bg-white p-6 rounded shadow h-fit">
        <h3 class="text-xl font-bold mb-4">Order Summary</h3>
        <?php
          $subtotal = 0;
          foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
          }
        ?>
        <div class="flex justify-between mb-2">
          <span>Subtotal</span>
          <span class="font-semibold">LKR<?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="flex justify-between mb-4">
          <span>Shipping</span>
          <span class="text-gray-500">Free</span>
        </div>
        <div class="border-t pt-4 flex justify-between text-lg font-bold">
          <span>Total</span>
          <span class="text-[#F4B14E]">LKR<?= number_format($subtotal, 2) ?></span>
        </div>
        <a href="checkout.php" class="block mt-6 bg-[#F4B14E] text-white text-center py-2 rounded hover:bg-yellow-600">Proceed to Checkout</a>
      </aside>
    <?php endif; ?>
  </main>

  <footer class="text-center py-6 text-sm text-gray-500">
    &copy; <?= date("Y") ?> Arcanum. All rights reserved. Nathaniel
  </footer>

  <script>
    function toggleSelectAll(checked) {
      document.querySelectorAll('input[name="delete[]"]').forEach(cb => cb.checked = checked);
    }
  </script>
</body>
</html>
