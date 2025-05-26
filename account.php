<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connect_db();
$customer_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];

    if ($username === '' || $email === '' || $phone === '') {
        $error = 'Please fill in all required fields.';
    } else {
        $passwordParam = ($password !== '') ? $password : null;
        if (updateUser($conn, $customer_id, $username, $email, $phone, $passwordParam)) {
            $success = 'Account details updated successfully.';
        } else {
            $error = 'Failed to update account.';
        }
    }
}

$user = getUserById($conn, $customer_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Account - Arcanum</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen">

  <!-- Header with Logo -->
  <header class="bg-white py-4 px-6 shadow-sm">
    <div class="flex justify-between items-center max-w-7xl mx-auto">
      <div class="w-1/3">
        <?php if ($user['is_admin'] == 1): ?>
          <a href="admin_dashboard.php" class="text-gray-700 font-medium hover:text-[#F4B14E] transition">Admin Dashboard</a>
        <?php endif; ?>
      </div>
      <div class="w-1/3 flex justify-center">
        <img src="/Arcanum/images/ARCANUM.png" alt="Arcanum Logo" class="h-10 object-contain" />
      </div>
      <div class="w-1/3 flex justify-end items-center gap-4">
        <a href="account.php" class="text-gray-700 font-medium hover:text-[#F4B14E] transition"><img src="images/account2.svg" alt=""></a>
        <a href="cart.php" class="relative text-gray-700 font-medium hover:text-[#F4B14E] transition"><img src="images/cart2.svg" alt=""></a>
        <a href="logout.php" class="text-red-600 font-semibold hover:underline ml-4">Logout</a>
      </div>
    </div>
  </header>

  <!-- Back to Home Button -->
  <div class="max-w-7xl mx-auto px-6 mt-4">
    <a href="index.php" class="inline-block text-sm text-[#F4B14E] hover:underline">
      ← Back to Home
    </a>
  </div>

  <main class="max-w-2xl mx-auto p-6 mt-8 bg-white rounded shadow">
    <h1 class="text-3xl font-bold mb-6">My Account</h1>

    <?php if ($success): ?>
      <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <form action="account.php" method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-semibold mb-1">Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="w-full border rounded px-4 py-2">
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full border rounded px-4 py-2">
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1">Phone Number</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required class="w-full border rounded px-4 py-2">
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1">Change Password</label>
        <input type="password" name="password" class="w-full border rounded px-4 py-2">
      </div>

      <button type="submit" class="bg-[#F4B14E] text-white px-6 py-2 rounded hover:bg-yellow-600">Update Account</button>
    </form>

    <h2 class="text-2xl font-bold mt-10 mb-4">My Orders</h2>

    <?php
    $orders = getUserOrders($conn, $customer_id);

    if (empty($orders)): ?>
      <p class="text-gray-600">You haven't placed any orders yet.</p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded">
          <thead class="bg-gray-100">
            <tr>
              <th class="text-left px-4 py-2 border">Order ID</th>
              <th class="text-left px-4 py-2 border">Date</th>
              <th class="text-left px-4 py-2 border">Status</th>
              <th class="text-left px-4 py-2 border">Total (LKR)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
              <tr class="hover:bg-gray-50 bg-white border-b">
                <td class="px-4 py-2 border"><?= htmlspecialchars($order['order_id']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($order['order_date']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars(ucfirst($order['order_status'])) ?></td>
                <td class="px-4 py-2 border">Rs. <?= number_format($order['total'], 2) ?></td>
              </tr>
              <tr>
                <td colspan="4" class="bg-gray-50 px-4 py-2 border border-t-0">
                  <div class="ml-4">
                    <p class="font-semibold text-sm mb-1">Items:</p>
                    <ul class="list-disc list-inside text-sm text-gray-700">
                      <?php
                        $items = getOrderItemsByOrderId($conn, $order['order_id']);
                        foreach ($items as $item): ?>
                          <li>
                            <?= htmlspecialchars($item['product_name']) ?> — Qty: <?= $item['quantity'] ?> × Rs. <?= number_format($item['price'], 2) ?>
                          </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </td>
              </tr>

            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </main>

</body>
</html>
