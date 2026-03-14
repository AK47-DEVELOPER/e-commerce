<?php
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/Cart.php';
require_once __DIR__ . '/../classes/Order.php';

$database = new Database();
$conn = $database->getConnection();

$user_obj = new User($conn);
$product_obj = new Product($conn);
$cart_obj = new Cart($conn);
$order_obj = new Order($conn);

?>
