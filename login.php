<?php
session_start();
require_once 'functions.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usernameOrEmail = trim($_POST['name']);
    $password = $_POST['password'];

    var_dump($usernameOrEmail, $password);
    $user = validate_login($usernameOrEmail, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['customer_id']; 
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid email, username or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Arcanum Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#F4B14E] to-white font-sans">

  <div class="bg-white p-10 rounded-xl shadow-lg w-full max-w-md">
    <form method="POST" class="space-y-6">
      <img src="images/ARCANUM.png" alt="Arcanum Logo" class="w-full mb-4">

      <h2 class="text-3xl font-semibold text-center text-gray-800">Login</h2>

      <?php if ($error): ?>
        <p class="text-center text-red-500 text-sm"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <div>
        <label for="name" class="block text-gray-700 font-medium mb-2">Username or Email</label>
        <input type="text" id="name" name="name" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F4B14E]" />
      </div>

      <div>
        <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
        <input type="password" id="password" name="password" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F4B14E]" />
      </div>

      <button type="submit"
        class="w-full py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-[#0e141d] transition-colors duration-300">
        Login
      </button>

      <p class="text-center text-sm text-gray-600">Don't have an account?
        <a href="register.php" class="text-blue-500 hover:underline">Sign up</a>
      </p>
    </form>
  </div>
</body>
</html>
