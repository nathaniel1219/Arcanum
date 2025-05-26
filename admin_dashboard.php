<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connect_db();
$customer_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT is_admin FROM customer WHERE customer_id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$is_admin = $stmt->get_result()->fetch_assoc()['is_admin'];

if ($is_admin != 1) {
    echo "Access denied.";
    exit();
}

$search_username = '';
$userdata = null;
$orders = [];
$message = '';

$user_list_stmt = $conn->prepare("SELECT username FROM customer WHERE is_admin = 0 ORDER BY username ASC");
$user_list_stmt->execute();
$user_list = $user_list_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_username'])) {
    $search_username = trim($_POST['search_username']);
} elseif (isset($_GET['username'])) {
    $search_username = trim($_GET['username']);
}

if ($search_username !== '') {
    $stmt = $conn->prepare("SELECT customer_id, username, email, phone, is_admin FROM customer WHERE username = ?");
    $stmt->bind_param("s", $search_username);
    $stmt->execute();
    $userdata = $stmt->get_result()->fetch_assoc();

    if ($userdata) {
        $stmt = $conn->prepare("SELECT order_id, total, order_date, order_status FROM `order` WHERE customer_id = ? ORDER BY order_date DESC");
        $stmt->bind_param("i", $userdata['customer_id']);
        $stmt->execute();
        $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $message = "User not found.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['order_status'];

    $allowed_statuses = ['Pending', 'Shipped'];
    if (in_array($new_status, $allowed_statuses)) {
        $stmt = $conn->prepare("UPDATE `order` SET order_status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        if ($stmt->execute()) {
            $message = "Order status updated successfully.";
            if ($userdata) {
                $stmt = $conn->prepare("SELECT order_id, total, order_date, order_status FROM `order` WHERE customer_id = ? ORDER BY order_date DESC");
                $stmt->bind_param("i", $userdata['customer_id']);
                $stmt->execute();
                $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
        } else {
            $message = "Failed to update order status.";
        }
    } else {
        $message = "Invalid order status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard - Arcanum</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6 flex flex-col">
  <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

  <a href="account.php" class="text-[#F4B14E] hover:underline mb-6 inline-block">← Back to Account</a>

  <?php if ($message): ?>
    <div class="mb-4 p-3 bg-yellow-100 text-yellow-800 rounded max-w-7xl"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <div class="flex gap-8 max-w-7xl flex-1">
    <aside class="w-64 bg-white p-4 rounded shadow h-[600px] overflow-y-auto">
      <h2 class="font-semibold text-lg mb-4 border-b pb-2">Users</h2>
      <ul>
        <?php foreach ($user_list as $user): ?>
          <li class="mb-2">
            <a
              href="?username=<?= urlencode($user['username']) ?>"
              class="block px-3 py-1 rounded hover:bg-yellow-100 <?= ($user['username'] === $search_username) ? 'bg-yellow-200 font-semibold' : '' ?>"
            >
              <?= htmlspecialchars($user['username']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </aside>

    <main class="flex-1 bg-white p-6 rounded shadow max-h-[600px] overflow-y-auto">
      <form method="POST" class="mb-8 flex items-center gap-2 max-w-md">
        <label class="block font-semibold" for="search_username">Search User by Username:</label>
        <input
          type="text"
          name="search_username"
          id="search_username"
          value="<?= htmlspecialchars($search_username) ?>"
          class="border rounded px-4 py-2 flex-grow"
          required
        />
        <button type="submit" class="bg-[#F4B14E] text-white px-4 py-2 rounded hover:bg-yellow-600">Search</button>
      </form>

      <?php if ($userdata): ?>
        <section class="mb-8">
          <h2 class="text-xl font-semibold mb-4 border-b pb-2">User Details</h2>
          <p><strong>Username:</strong> <?= htmlspecialchars($userdata['username']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($userdata['email']) ?></p>
          <p><strong>Phone:</strong> <?= htmlspecialchars($userdata['phone']) ?></p>
          <p><strong>Is Admin:</strong> <?= $userdata['is_admin'] ? 'Yes' : 'No' ?></p>
        </section>

        <section>
          <h2 class="text-xl font-semibold mb-4 border-b pb-2">User Orders</h2>
          <?php if (count($orders) > 0): ?>
            <table class="w-full table-auto border-collapse border border-gray-300">
              <thead>
                <tr class="bg-gray-200">
                  <th class="border border-gray-300 px-3 py-1 text-left">Order ID</th>
                  <th class="border border-gray-300 px-3 py-1 text-left">Total</th>
                  <th class="border border-gray-300 px-3 py-1 text-left">Order Date</th>
                  <th class="border border-gray-300 px-3 py-1 text-left">Status</th>
                  <th class="border border-gray-300 px-3 py-1 text-left">Change Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $order): ?>
                  <tr>
                    <td class="border border-gray-300 px-3 py-1"><?= $order['order_id'] ?></td>
                    <td class="border border-gray-300 px-3 py-1">LKR<?= number_format($order['total'], 2) ?></td>
                    <td class="border border-gray-300 px-3 py-1"><?= htmlspecialchars($order['order_date']) ?></td>
                    <td class="border border-gray-300 px-3 py-1"><?= htmlspecialchars($order['order_status']) ?></td>
                    <td class="border border-gray-300 px-3 py-1">
                      <form method="POST" class="flex items-center gap-2">
                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>" />
                        <select name="order_status" class="border rounded px-2 py-1">
                          <option value="Pending" <?= $order['order_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                          <option value="Shipped" <?= $order['order_status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                        </select>
                        <button type="submit" name="update_order_status" class="bg-[#F4B14E] text-white px-3 py-1 rounded hover:bg-yellow-600">Update</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <p>This user has no orders.</p>
          <?php endif; ?>
        </section>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
