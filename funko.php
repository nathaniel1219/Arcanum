<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$username = $_SESSION['username'];
$conn = connect_db();

// Get only Funko products
$category = 'funko pop';
$products = getProductsByCategory($conn, $category);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Arcanum - Funko</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen font-sans text-gray-800">

  <!-- Header -->
  <header class="bg-white py-4 px-6">
    <div class="flex justify-between items-center max-w-7xl mx-auto">
      <div class="w-1/3"></div>
      <div class="w-1/3 flex justify-center">
        <a href="index.php">
          <img src="/Arcanum/images/ARCANUM.png" alt="Arcanum Logo" class="h-10 object-contain" />
        </a>
      </div>
      <div class="w-1/3 flex justify-end items-center gap-4">
        <a href="account.php" class="text-gray-700 font-medium hover:text-[#F4B14E] transition"><img src="images/account2.svg" alt=""></a>
        <a href="cart.php" class="relative text-gray-700 font-medium hover:text-[#F4B14E] transition"><img src="images/cart2.svg" alt=""></a>
      </div>
    </div>

    <!-- Navbar -->
    <nav class="bg-white shadow-md mt-4">
      <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex justify-center space-x-12">
          <?php
            $pages = [
              "index"   => "Home",
              "pokemon" => "Pokemon",
              "yugioh"  => "Yu-Gi-Oh",
              "funko"   => "Funko"
            ];
            $current = basename($_SERVER['PHP_SELF'], ".php");
            foreach ($pages as $file => $label):
              $isActive = $file === $current;
          ?>
            <a href="<?= $file ?>.php"
               class="group relative text-lg font-semibold <?= $isActive ? 'text-black' : 'text-gray-700' ?>">
              <?= $label ?>
              <span class="absolute left-0 -bottom-1 w-full h-0.5 bg-[#F4B14E] scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left <?= $isActive ? 'scale-x-100' : '' ?>"></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="p-8 max-w-7xl mx-auto">
    <div class="text-center mb-12">
      <h2 class="text-4xl font-bold mb-2">Funko Pops</h2>
      <p class="text-gray-700 text-lg">Explore our collectible Funko Pop figures</p>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($products as $index => $product): ?>
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
          <img 
            src="/Arcanum/images/products/<?= htmlspecialchars($product['image_url']) ?>" 
            alt="<?= htmlspecialchars($product['product_name']) ?>" 
            class="w-full h-48 object-cover cursor-pointer" 
            onclick="openModal(<?= $index ?>)"
          >
          <div class="p-4">
            <h3 class="text-xl font-semibold"><?= htmlspecialchars($product['product_name']) ?></h3>
            <p class="text-gray-600 mt-1"><?= htmlspecialchars($product['description']) ?></p>
            <p class="mt-2 font-semibold text-[#F4B14E]">LKR<?= number_format($product['price'], 2) ?></p>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($product['sub_category']) ?></p>
            <button 
              onclick="openModal(<?= $index ?>)" 
              class="mt-4 bg-[#F4B14E] text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition"
            >View</button>
          </div>
        </div>

        <!-- Modal -->
        <div id="modal-<?= $index ?>" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
          <div class="bg-white rounded-xl max-w-xl w-full p-6 relative shadow-lg overflow-y-auto max-h-[90vh]">
            <button onclick="closeModal(<?= $index ?>)" class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">&times;</button>
            <div class="flex flex-col items-center">
              <img 
                src="/Arcanum/images/products/<?= htmlspecialchars($product['image_url']) ?>" 
                alt="<?= htmlspecialchars($product['product_name']) ?>" 
                class="w-full max-h-[400px] object-contain rounded-md mb-4"
              >
              <h3 class="text-2xl font-bold text-center"><?= htmlspecialchars($product['product_name']) ?></h3>
              <p class="text-gray-600 mt-2 text-center"><?= htmlspecialchars($product['description']) ?></p>
              <p class="text-gray-700 text-sm mt-2 text-center"><?= nl2br(htmlspecialchars($product['details'])) ?></p>
              <p class="mt-4 font-semibold text-[#F4B14E] text-lg">LKR<?= number_format($product['price'], 2) ?></p>
              <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($product['sub_category']) ?></p>

              <!-- AJAX Add to Cart Button -->
              <button 
                onclick="addToCart(<?= $product['product_id'] ?>)" 
                class="w-full bg-[#F4B14E] text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition"
              >Add to Cart</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <!-- Toast Notification -->
  <div id="toast" class="fixed bottom-6 right-6 bg-green-500 text-white py-3 px-4 rounded shadow-lg hidden z-50 transition-opacity"></div>

  <!-- Footer -->
  <footer class="text-center py-6 text-sm text-gray-500 mt-12">
    &copy; <?= date("Y") ?> Arcanum. All rights reserved. Nathaniel.
  </footer>

  <!-- Scripts -->
  <script>
    function openModal(index) {
      document.getElementById(`modal-${index}`).classList.remove('hidden');
    }

    function closeModal(index) {
      document.getElementById(`modal-${index}`).classList.add('hidden');
    }

    function showToast(message) {
      const toast = document.getElementById('toast');
      toast.textContent = message;
      toast.classList.remove('hidden');
      toast.classList.add('opacity-100');
      setTimeout(() => {
        toast.classList.add('hidden');
        toast.classList.remove('opacity-100');
      }, 3000);
    }

    function addToCart(productId) {
      fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `product_id=${productId}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showToast('✅ ' + data.message);
        } else {
          showToast('❌ ' + data.message);
        }
      })
      .catch(error => {
        showToast('❌ Failed to add to cart');
      });
    }
  </script>
</body>
</html>
