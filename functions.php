<?php

function connect_db() {
    $host = "localhost";
    $user = "root";
    $password = ""; 
    $dbname = "arcanum_db";

    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}

/* register logic */
function register_user($username, $email, $phone, $password, $confirm_password) {
    $conn = connect_db();

    if ($password !== $confirm_password) {
        return ['success' => false, 'message' => "Passwords do not match."];
    }

    // Check for existing email
    $stmt = $conn->prepare("SELECT * FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return ['success' => false, 'message' => "An account with this email already exists."];
    }

    // Register the user WITHOUT hashing the password
    $stmt = $conn->prepare("INSERT INTO customer (username, password, email, phone, is_admin) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("ssss", $username, $password, $email, $phone);

    if ($stmt->execute()) {
        return ['success' => true, 'message' => "Registration successful! You can now log in."];
    } else {
        return ['success' => false, 'message' => "Something went wrong. Please try again."];
    }
}

/* login stuff */
function validate_login($usernameOrEmail, $password) {
    $conn = connect_db();
    if (!$conn) {
        return false;
    }

    error_log("Trying login for: $usernameOrEmail");
    $sql = "SELECT * FROM customer WHERE username = ? OR email = ? LIMIT 1";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Compare plain text passwords directly (not secure but works for now)
            if ($password === $user['password']) {
                $stmt->close();
                $conn->close();
                return $user;
            }
        }
        $stmt->close();
    }
    $conn->close();
    return false;
}

/* account functions */
function getUserById($conn, $customer_id) {
    $stmt = $conn->prepare("SELECT username, email, phone, is_admin FROM customer WHERE customer_id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateUser($conn, $customer_id, $username, $email, $phone, $password = null) {
    if ($password !== null) {
        // No hashing here — just update password as-is
        $stmt = $conn->prepare("UPDATE customer SET username = ?, email = ?, phone = ?, password = ? WHERE customer_id = ?");
        $stmt->bind_param("ssssi", $username, $email, $phone, $password, $customer_id);
    } else {
        $stmt = $conn->prepare("UPDATE customer SET username = ?, email = ?, phone = ? WHERE customer_id = ?");
        $stmt->bind_param("sssi", $username, $email, $phone, $customer_id);
    }
    return $stmt->execute();
}

function getUserOrders($conn, $customer_id) {
    $stmt = $conn->prepare("SELECT order_id, order_date, order_status, total FROM `order` WHERE customer_id = ? ORDER BY order_date DESC");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
    return $orders;
}

function getOrderItemsByOrderId($conn, $order_id) {
    $stmt = $conn->prepare("
        SELECT oi.product_id, p.product_name, oi.quantity, oi.price
        FROM orderitem oi
        JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}







/**
 * Returns all products from the database
 */
function getAllProducts($conn) {
    $products = [];

    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    return $products;
}


function getProductsByCategory($conn, $category) {
    $products = [];
    $sql = "SELECT * FROM products WHERE sub_category = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    $stmt->close();
    return $products;
}

/* cart logic */
function getOrCreateCart($conn, $user_id) {
    $stmt = $conn->prepare("SELECT cart_id FROM cart WHERE customer_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $cart = $result->fetch_assoc();
        return $cart['cart_id'];
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (customer_id) VALUES (?)");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->insert_id;
    }
}

function addToCart($conn, $user_id, $product_id, $quantity = 1) {
    $cart_id = getOrCreateCart($conn, $user_id);

    $stmt = $conn->prepare("SELECT * FROM cartitem WHERE cart_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $cart_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE cartitem SET quantity = quantity + ? WHERE cart_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $cart_id, $product_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO cartitem (cart_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
        $stmt->execute();
    }
}

function getCartItems($conn, $user_id) {
    $cart_id = getOrCreateCart($conn, $user_id);

    $stmt = $conn->prepare("
        SELECT p.product_id, p.product_name, p.image_url, p.price, ci.quantity
        FROM cartitem ci
        JOIN products p ON ci.product_id = p.product_id
        WHERE ci.cart_id = ?
    ");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }

    return $items;
}

function removeCartItems($conn, $cart_id, $product_ids) {
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids) + 1);
    $stmt = $conn->prepare("DELETE FROM cartitem WHERE cart_id = ? AND product_id IN ($placeholders)");
    $params = array_merge([$cart_id], $product_ids);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
}


function updateCartQuantities($conn, $cart_id, $updates) {
    foreach ($updates as $product_id => $quantity) {
        $stmt = $conn->prepare("UPDATE cartitem SET quantity = ? WHERE cart_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $cart_id, $product_id);
        $stmt->execute();
    }
}


//checkout and order logic
function createOrder($conn, $customer_id, $total) {
    $stmt = $conn->prepare("INSERT INTO `order` (customer_id, total, order_date, order_status) VALUES (?, ?, NOW(), 'pending')");
    $stmt->bind_param("id", $customer_id, $total);
    $stmt->execute();
    return $stmt->insert_id;
}

function insertOrderItem($conn, $order_id, $product_id, $quantity, $price) {
    $stmt = $conn->prepare("INSERT INTO orderitem (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
    $stmt->execute();
}

function clearCart($conn, $customer_id) {
    $cart_id = getOrCreateCart($conn, $customer_id);
    $conn->query("DELETE FROM cartitem WHERE cart_id = $cart_id");
}



/* the admin dashboard logic */
function isAdmin($conn, $customer_id) {
    $stmt = $conn->prepare("SELECT is_admin FROM customer WHERE customer_id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return ($result && $result['is_admin'] == 1);
}

function getAllUsers($conn) {
    $stmt = $conn->prepare("SELECT customer_id, username FROM customer ORDER BY username");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getUserByUsername($conn, $username) {
    $stmt = $conn->prepare("SELECT customer_id, username, email, phone, is_admin FROM customer WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getOrdersByCustomerId($conn, $customer_id) {
    $stmt = $conn->prepare("SELECT order_id, total, order_date, order_status FROM `order` WHERE customer_id = ? ORDER BY order_date DESC");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function updateOrderStatus($conn, $order_id, $new_status) {
    $allowed_statuses = ['Pending', 'Shipped'];
    if (!in_array($new_status, $allowed_statuses)) {
        return false;
    }
    $stmt = $conn->prepare("UPDATE `order` SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    return $stmt->execute();
}