<?php
    session_start();
    if ($_SESSION['permission'] != 'admin') {
        header('Location: index.php');
    }
    $activesidebar = 1;
    $namepage = "ข้อมูลผู้ใช้";
    $user_fullname = $_SESSION['user_fullname'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ข้อมูลผู้ใช้</title>
    <link rel="icon" href="./assets/image/icon.png" type="image/png">
    <script src='https://code.jquery.com/jquery-3.7.1.js' integrity='sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=' crossorigin='anonymous'></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link id="pagestyle" href="./assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-100">
    <div class="container-fluid">
        <?php include('./component/sidebar.php'); ?>
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
            <?php include('./component/navbaradmin.php'); ?>
            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-6">
                                    <h6><?php echo $namepage; ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">รหัสพนักงาน</th>
                                            <th style="text-align: center;">ชื่อ - นามสกุล</th>
                                            <th style="text-align: center;">ชื่อผู้ใช้งาน</th>
                                            <th style="text-align: center;">เบอร์โทรศัพท์</th>
                                            <th style="text-align: center;">แต้ม</th>
                                            <th style="text-align: center;">สิทธิ์</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include ('./component/connectdatabase.php');
                                            $sql = 'select * from tb_users order by user_id asc';//ตารางแสดงข้อมูลผู้ใช้ดึงข้อมูลผู้ใช้ทั้งหมดจากฐานข้อมูล tb_users
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $row["user_id"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["user_fname"] . " " . $row["user_lname"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["user_username"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["user_phone"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["user_point"]; ?></td>
                                            <td style="text-align: center;"><?php if ($row["user_rules"] === "member") { echo "ลูกค้า"; } else { echo "ผู้ดูแลระบบ"; }  ?></td>
                                        </tr>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<script>
    $(document).ready(function() {//เปิดใช้งาน DataTables
        $('.table').DataTable({
            language: {
                emptyTable: "ไม่มีข้อมูลที่จะแสดง",
                url: "./assets/js/lang/th.json" // ใช้ไฟล์ที่อยู่ในเซิร์ฟเวอร์แทน CDN
            }
        });
    });
</script>