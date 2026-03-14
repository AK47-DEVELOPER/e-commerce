<?php
session_start();
include('./component/connectdatabase.php'); // เชื่อมต่อฐานข้อมูล

$popup_message = ""; // เก็บสคริปต์ popup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['user_fname'] ?? '';
    $last_name  = $_POST['user_lname'] ?? '';
    $username   = $_POST['user_username'] ?? '';
    $password   = $_POST['user_password'] ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';
    $email      = $_POST['user_email'] ?? '';
    $phone      = $_POST['user_tel'] ?? '';
    $address    = $_POST['user_address'] ?? '';

    // ตรวจสอบว่ากรอกครบทุกช่อง
    if ($first_name && $last_name && $username && $password && $confirm && $email && $phone && $address) {

        // ตรวจสอบรหัสผ่านตรงกัน
        if ($password !== $confirm) {
            $popup_message = '
                Swal.fire({
                    title: "แจ้งเตือน",
                    text: "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน",
                    icon: "warning",
                    confirmButtonText: "ตกลง"
                });
            ';
        } else {
            // ใช้ OOP Class User ในการสมัครสมาชิก (User.php จัดการเช็คชื่อซ้ำและ hash password ให้แล้ว)
            $result = $user_obj->register($first_name, $last_name, $username, $password, $email, $phone, $address);

            if ($result === "username_exists") {
                $popup_message = '
                    Swal.fire({
                        title: "แจ้งเตือน",
                        text: "ชื่อผู้ใช้นี้ถูกใช้แล้ว",
                        icon: "warning",
                        confirmButtonText: "ตกลง"
                    });
                ';
            } elseif ($result === true) {
                $popup_message = '
                    Swal.fire({
                        title: "สมัครสมาชิกสำเร็จ!",
                        text: "ยินดีต้อนรับ ' . htmlspecialchars($username) . '!",
                        icon: "success",
                        confirmButtonText: "ตกลง"
                    }).then(function() {
                        window.location.href = "login.php";
                    });
                ';
            } else {
                $popup_message = '
                    Swal.fire({
                        title: "ข้อผิดพลาด",
                        text: "เกิดข้อผิดพลาด กรุณาลองใหม่",
                        icon: "error",
                        confirmButtonText: "ตกลง"
                    });
                ';
            }
        }
    } else {
        $popup_message = '
            Swal.fire({
                title: "แจ้งเตือน",
                text: "กรุณากรอกข้อมูลให้ครบถ้วน",
                icon: "warning",
                confirmButtonText: "ตกลง"
            });
        ';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link rel="icon" href="./assets/image/icon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="font-family: 'Prompt', sans-serif;">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="card p-4 shadow-lg rounded-4">
                <h3 class="text-center mb-4">สมัครสมาชิก</h3>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">ชื่อจริง</label>
                        <input type="text" class="form-control" name="user_fname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">นามสกุล</label>
                        <input type="text" class="form-control" name="user_lname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ชื่อผู้ใช้งาน</label>
                        <input type="text" class="form-control" name="user_username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">รหัสผ่าน</label>
                        <input type="password" class="form-control" name="user_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ยืนยันรหัสผ่าน</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">อีเมล์</label>
                        <input type="email" class="form-control" name="user_email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">เบอร์โทรศัพท์</label>
                        <input type="tel" class="form-control" name="user_tel" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ที่อยู่</label>
                        <textarea class="form-control" name="user_address" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">บันทึก</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="login.php">คุณมีบัญชีอยู่แล้วใช่มั้ย? เข้าสู่ระบบ</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($popup_message): ?>
<script>
    <?= $popup_message ?>
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
