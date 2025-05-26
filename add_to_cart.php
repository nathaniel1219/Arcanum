<?php
session_start();
require_once 'functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
  $userId = $_SESSION['user_id'];
  $productId = intval($_POST['product_id']);

  $conn = connect_db();

  // Create cart if it doesn't exist
  $cartQuery = $conn->prepare("SELECT cart_id FROM cart WHERE customer_id = ?");
  $cartQuery->bind_param("i", $userId);
  $cartQuery->execute();
  $cartResult = $cartQuery->get_result();

  if ($cartResult->num_rows === 0) {
    $createCart = $conn->prepare("INSERT INTO cart (customer_id) VALUES (?)");
    $createCart->bind_param("i", $userId);
    $createCart->execute();
    $cartId = $createCart->insert_id;
  } else {
    $cartId = $cartResult->fetch_assoc()['cart_id'];
  }

  // Check if item is already in cart
  $checkItem = $conn->prepare("SELECT * FROM cartitem WHERE cart_id = ? AND product_id = ?");
  $checkItem->bind_param("ii", $cartId, $productId);
  $checkItem->execute();
  $itemResult = $checkItem->get_result();

  if ($itemResult->num_rows > 0) {
    // Update quantity
    $update = $conn->prepare("UPDATE cartitem SET quantity = quantity + 1 WHERE cart_id = ? AND product_id = ?");
    $update->bind_param("ii", $cartId, $productId);
    $update->execute();
  } else {
    // Insert new item
    $insert = $conn->prepare("INSERT INTO cartitem (cart_id, product_id, quantity) VALUES (?, ?, 1)");
    $insert->bind_param("ii", $cartId, $productId);
    $insert->execute();
  }

  echo json_encode(['success' => true, 'message' => 'Product added to cart']);
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
