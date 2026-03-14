<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('./component/connectdatabase.php');

// ตรวจสอบข้อมูลผู้ใช้
$user_arr = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_arr = $user_obj->getById($user_id);
}

// ตรวจสอบจำนวนสินค้าในตะกร้า
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $cart_count = $cart_obj->getCount($_SESSION['user_id']);
}

// ตรวจสอบการออกจากระบบ
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    echo "<script>window.location.href = 'logout.php';</script>";
    exit();
}

if (isset($_POST['update_profile'])) {
    $fname   = $_POST['first_name'];
    $lname   = $_POST['last_name'];
    $email   = $_POST['email'];
    $tel     = $_POST['phone'];
    $address = $_POST['address'];

    $password = $_POST['user_password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!empty($password) && $password !== $confirm) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน',
                confirmButtonText: 'ตกลง'
            });
        </script>";
    } else {
        $update_success = $user_obj->updateProfile($_SESSION['user_id'], $fname, $lname, $email, $tel, $address, $password);

        if ($update_success) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: 'อัปเดตข้อมูลเรียบร้อยแล้ว',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    window.location.href = 'index.php'; // กลับไปหน้าหลัก
                });
            </script>";
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'ผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล',
                    confirmButtonText: 'ตกลง'
                });
            </script>";
        }
    }
}
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">ร้านโมเดล</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- หน้าแรก -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <!-- เมนูสินค้า -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Product
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="product.php">All</a></li>
                    </ul>
                </li>

                <!-- ตะกร้าสินค้า -->
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($cart_count > 0) { ?>
                            <span class="badge bg-danger"><?php echo $cart_count; ?></span>
                        <?php } ?>
                    </a>
                </li>

                <!-- เมนูผู้ใช้ -->
                <?php if ($user_arr) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($user_arr["first_name"]); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                                        Profile
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="order.php">Order</a></li>
                            <?php if ($user_arr["role"] == "admin" || $user_arr["role"] == "factory") { ?>
                                <li><a class="dropdown-item" href="dashboard/index.php">Dashboard</a></li>
                            <?php } ?>
                            <li><a class="dropdown-item" href="?action=logout">Logout</a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="fas fa-user"></i> Login
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
     <!-- Modal แก้ไขโปรไฟล์ -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="post" action="">
        <div class="modal-header">
          <h5 class="modal-title">แก้ไขโปรไฟล์</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        
        <div class="modal-body row g-3">
          <!-- Username (แก้ไขไม่ได้) -->
          <div class="col-md-6">
            <label class="form-label">ชื่อผู้ใช้</label>
            <input type="text" class="form-control" 
                   value="<?= htmlspecialchars($user_arr['username'] ?? '') ?>" readonly>
          </div>

          <!-- Password -->
          <div class="col-md-6">
            <label class="form-label">รหัสผ่านใหม่</label>
            <input type="password" class="form-control" name="user_password" placeholder="กรอกรหัสผ่านใหม่ (ถ้ามี)">
          </div>

          <!-- Confirm Password -->
          <div class="col-md-6">
            <label class="form-label">ยืนยันรหัสผ่าน</label>
            <input type="password" class="form-control" name="confirm_password" placeholder="ยืนยันรหัสผ่านใหม่">
          </div>

          <!-- Firstname -->
          <div class="col-md-6">
            <label class="form-label">ชื่อจริง</label>
            <input type="text" class="form-control" name="first_name" 
                   value="<?= htmlspecialchars($user_arr['first_name'] ?? '') ?>" required>
          </div>

          <!-- Lastname -->
          <div class="col-md-6">
            <label class="form-label">นามสกุล</label>
            <input type="text" class="form-control" name="last_name" 
                   value="<?= htmlspecialchars($user_arr['last_name'] ?? '') ?>" required>
          </div>

          <!-- Email -->
          <div class="col-md-6">
            <label class="form-label">อีเมล</label>
            <input type="email" class="form-control" name="email" 
                   value="<?= htmlspecialchars($user_arr['email'] ?? '') ?>" required>
          </div>

          <!-- Phone -->
          <div class="col-md-6">
            <label class="form-label">เบอร์โทร</label>
            <input type="text" class="form-control" name="phone" 
                   value="<?= htmlspecialchars($user_arr['phone'] ?? '') ?>" maxlength="10" required>
          </div>

          <!-- Address -->
          <div class="col-md-12">
            <label class="form-label">ที่อยู่</label>
            <textarea class="form-control" name="address" rows="3" required><?= htmlspecialchars($user_arr['address'] ?? '') ?></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" name="update_profile" class="btn btn-success">บันทึก</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
        </div>
      </form>
    </div>
  </div>
</div>
</nav>
