<?php
session_start();
include('./component/connectdatabase.php'); // เชื่อมต่อฐานข้อมูล

$alert = ""; // เก็บข้อความแจ้งเตือนไว้

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ใช้ OOP Class User ในการเข้าสู่ระบบ (เช็ครหัสผ่านด้วย password_verify แล้ว)
    $auth_user = $user_obj->login($username, $password);

    if ($auth_user) {
        $_SESSION['user_id']       = $auth_user['id'];
        $_SESSION['user_username'] = $auth_user['username'];
        $_SESSION['user_rules']    = $auth_user['role'];

        // แยก redirect ตามสิทธิ์
        if ($auth_user['role'] === 'admin') {
            $alert = "Swal.fire({
                title: 'เข้าสู่ระบบสำเร็จ!',
                text: 'ยินดีต้อนรับ {$auth_user['username']}!',
                icon: 'success'
            }).then(() => { window.location.href='dashboard/user.php'; });";
        } elseif ($auth_user['role'] === 'factory') {
            $alert = "Swal.fire({
                title: 'เข้าสู่ระบบสำเร็จ!',
                text: 'ยินดีต้อนรับ {$auth_user['username']}!',
                icon: 'success'
            }).then(() => { window.location.href='dashboard/order_fa.php'; });";
        } else {
            $alert = "Swal.fire({
                title: 'เข้าสู่ระบบสำเร็จ!',
                text: 'ยินดีต้อนรับ {$auth_user['username']}!',
                icon: 'success'
            }).then(() => { window.location.href='index.php'; });";
        }

    } else {
        $alert = "Swal.fire('ข้อมูลไม่ถูกต้อง!','ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง','error');";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="icon" href="./assets/image/icon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="font-family: 'Prompt', sans-serif;">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="width: 400px; border-radius: 16px;">
            <div class="card-header text-center bg-white border-0">
                <h3>เข้าสู่ระบบ</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">ชื่อผู้ใช้งาน</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">รหัสผ่าน</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-success w-100">เข้าสู่ระบบ</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="register.php">ยังไม่มีบัญชีใช่มั้ย? สมัครสมาชิก</a>
                </div>
            </div>
        </div>
    </div>

    <?php if ($alert): ?>
    <script>
        <?= $alert ?>
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
