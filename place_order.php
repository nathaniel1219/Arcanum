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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
    $payment = $_POST['payment_method'];
    $total = floatval($_POST['total']);

    $order_id = createOrder($conn, $user_id, $total);

    foreach ($cartItems as $item) {
        insertOrderItem($conn, $order_id, $item['product_id'], $item['quantity'], $item['price']);
    }

    clearCart($conn, $user_id);

    $_SESSION['order_success'] = [
        'order_id' => $order_id,
        'name' => $name,
        'address' => $address,
        'city' => $city,
        'phone' => $phone,
        'payment' => $payment,
        'total' => $total
    ];

    header("Location: order_success.php");
    exit();
}
?>
