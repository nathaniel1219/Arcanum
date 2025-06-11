<?php
require_once 'functions.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $result = register_user($username, $email, $phone, $password, $confirm_password);
    if ($result['success']) {
        $success = $result['message'];
    } else {
        $error = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Arcanum Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Tilt+Neon&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#F4B14E] to-white font-sans" style="font-family: 'Tilt Neon', sans-serif;">

  <div class="bg-white p-10 rounded-xl shadow-lg w-full max-w-md">
    <form method="POST" action="" class="space-y-6">
      <img src="images/ARCANUM.png" alt="Arcanum Logo" class="w-full mb-4">

      <h2 class="text-3xl font-semibold text-center text-gray-800">Sign Up</h2>

      <?php if ($error): ?>
        <p class="text-center text-red-500 text-sm"><?= htmlspecialchars($error) ?></p>
      <?php elseif ($success): ?>
        <p class="text-center text-green-500 text-sm"><?= htmlspecialchars($success) ?></p>
      <?php endif; ?>

      <div>
        <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
        <input type="text" id="username" name="username" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F4B14E]" />
      </div>

      <div>
        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
        <input type="email" id="email" name="email" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F4B14E]" />
      </div>

      <div>
        <label for="phone" class="block text-gray-700 font-medium mb-2">Phone</label>
        <input type="text" id="phone" name="phone" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F4B14E]" />
      </div>

      <div>
        <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
        <input type="password" id="password" name="password" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F4B14E]" />
      </div>

      <div>
        <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F4B14E]" />
      </div>

      <button type="submit"
        class="w-full py-2 bg-[#F4B14E] text-white font-semibold rounded-md hover:bg-yellow-600 transition-colors duration-300">
        Register
      </button>

      <p class="text-center text-sm text-gray-600">Already have an account?
        <a href="login.php" class="text-blue-500 hover:underline">Login</a>
      </p>
    </form>
  </div>
</body>
</html>
