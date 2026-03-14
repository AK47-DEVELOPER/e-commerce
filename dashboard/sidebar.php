<?php
if (!isset($_SESSION)) {
    session_start();
}
include('../component/connectdatabase.php');

// ตรวจสอบว่ามีการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT user_rules FROM tb_users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_role = $user['user_rules'];
?>

<!-- Sidebar -->
<div class="d-flex flex-column p-3 bg-dark text-white" style="width: 250px; height:100vh; position:fixed;">
    <h4 class="text-center mb-4">Dashboard</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <?php if ($user_role === 'admin'): ?>
            <li class="nav-item">
                <a href="user.php" class="nav-link text-white">👤 Users</a>
            </li>
            <li>
                <a href="product.php" class="nav-link text-white">📦 Products</a>
            </li>
            <li>
                <a href="order.php" class="nav-link text-white">🛒 Orders</a>
            </li>
        <?php elseif ($user_role === 'factory'): ?>
            <li>
                <a href="order_fa.php" class="nav-link text-white">🛒 Orders</a>
            </li>
        <?php endif; ?>
    </ul>
    <hr>
    <div class="text-center">
        <button id="logoutBtn" class="btn btn-danger btn-sm">Logout</button>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById("logoutBtn").addEventListener("click", function() {
    Swal.fire({
        title: "ยืนยันการออกจากระบบ?",
        text: "คุณต้องการออกจากระบบใช่หรือไม่",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "ใช่, ออกจากระบบ",
        cancelButtonText: "ยกเลิก"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "../logout.php";
        }
    });
});
</script>
