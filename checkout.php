<?php
session_start();
include("./component/connectdatabase.php");

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_checkout'])) {
    
    // ใช้งานคลาส Order เพื่อดำเนินการสั่งซื้อผ่าน Transaction สต๊อก ฯลฯ
    $result = $order_obj->checkout($user_id);

    if ($result === "success") {
        echo "<script>alert('สั่งซื้อสำเร็จ! รอการตรวจสอบและชำระเงินต่อไป'); window.location='order.php';</script>";
        exit();
    } elseif ($result === "empty_cart") {
        echo "<script>alert('ไม่มีสินค้าในตะกร้า'); window.location='cart.php';</script>";
        exit();
    } elseif ($result === "user_not_found") {
        echo "<script>alert('ไม่พบข้อมูลผู้ใช้'); window.location='login.php';</script>";
        exit();
    } else {
        // อาจจะเป็น stock_error หรือข้อผิดพลาดอื่นๆ จาก Exception
        echo "<script>alert('พบข้อผิดพลาด: " . htmlspecialchars($result) . "'); window.location='cart.php';</script>";
        exit();
    }

} else {
    header("Location: cart.php");
    exit();
}
