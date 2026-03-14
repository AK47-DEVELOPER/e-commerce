<?php
session_start();
session_unset();
session_destroy(); // ลบ session ทั้งหมด
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Logging out...</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body style="font-family: 'Prompt', sans-serif;">
<script>
    Swal.fire({
        title: 'ออกจากระบบสำเร็จ!',
        text: 'คุณได้ออกจากระบบเรียบร้อยแล้ว',
        icon: 'success',
        confirmButtonText: 'ตกลง'
    }).then(function() {
        window.location.href = 'index.php';
    });
</script>
</body>
</html>