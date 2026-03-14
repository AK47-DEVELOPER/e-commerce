<?php
    session_start();
    if ($_SESSION['permission'] != 'admin') {
        header('Location: index.php');
    }
    $activesidebar = 3;
    $namepage = "ข้อมูลของรางวัล";
    $user_fullname = $_SESSION['user_fullname'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ข้อมูลของรางวัล</title>
    <link rel="icon" href="./assets/image/icon.png" type="image/png">
    <script src='https://code.jquery.com/jquery-3.7.1.js' integrity='sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=' crossorigin='anonymous'></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                                <div class="col-6 text-end">
                                    <?php include './component/modal/modaladdreward.php'; ?>
                                    <?php include './component/modal/modaleditreward.php'; ?>
                                    <a style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#addreward">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">รหัสของรางรัล</th>
                                            <th style="text-align: center;">ชื่อของรางวัล</th>
                                            <th style="text-align: center;">รายละเอียดของรางรัล</th>
                                            <th style="text-align: center;">แต้มที่ต้องได้</th>
                                            <th style="text-align: center;">ชื่อภาพของรางวัล</th>
                                            <th style="text-align: center;">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include ('./component/connectdatabase.php');
                                            $sql = 'select * from tb_rewards order by rewa_id asc';//ตารางข้อมูลรางวัล
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $row["rewa_id"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["rewa_name"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["rewa_details"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["rewa_point"]; ?> คะแนน</td>
                                            <td style="text-align: center;"><?php echo $row["rewa_img"]; ?></td>
                                            <td style="text-align: center;">
                                                <a class="text-warning me-2 edit-reward" style="cursor: pointer;" 
                                                    title="แก้ไข" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editreward" 
                                                    data-rewa-id="<?php echo $row["rewa_id"]; ?>"
                                                    data-rewa-name="<?php echo $row["rewa_name"]; ?>"
                                                    data-rewa-details="<?php echo $row["rewa_details"]; ?>"
                                                    data-rewa-type="<?php echo $row["rewa_type"]; ?>"
                                                    data-rewa-price="<?php echo $row["rewa_price"]; ?>"
                                                    data-rewa-point="<?php echo $row["rewa_point"]; ?>"
                                                    data-rewa-img="<?php echo $row["rewa_img"]; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a class="text-danger delete-item" style="cursor: pointer;"  data-rewa-id="<?php echo $row["rewa_id"]; ?>" data-rewa-name="<?php echo $row["rewa_name"]; ?>" data-rewa-img="<?php echo $row["rewa_img"]; ?>" title="ลบ"><i class="fas fa-trash-alt"></i></a>
                                            </td>
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
    $(document).ready(function() {
        $('.table').DataTable({
            language: {
                emptyTable: "ไม่มีข้อมูลที่จะแสดง",
                url: "./assets/js/lang/th.json" // ใช้ไฟล์ที่อยู่ในเซิร์ฟเวอร์แทน CDN
            }
        });

        // เมื่อคลิกปุ่มแก้ไข
        $('.edit-reward').click(function(){
            $('#edit_reward')[0].reset();
            $('#edit-rewa-imageold').show();
            $('#edit-rewa-imagenew').hide();
            // ดึงข้อมูลจาก data attributes
            var rewaId = $(this).data('rewa-id');
            var rewaName = $(this).data('rewa-name');
            var rewaDetails = $(this).data('rewa-details');
            var rewaType = $(this).data('rewa-type');
            var rewaPrice = $(this).data('rewa-price');
            var rewaPoint = $(this).data('rewa-point');
            var rewaImg = $(this).data('rewa-img');
            // แสดงข้อมูลใน modal
            $('#edit-rewa-id').val(rewaId);
            $('#edit-rewa-name').val(rewaName);
            $('#edit-rewa-details').val(rewaDetails);
            $('#edit-rewa-type').val(rewaType);
            $('#edit-rewa-price').val(rewaPrice);
            $('#edit-rewa-point').val(rewaPoint);
            $('#edit-rewa-imageold').val(rewaImg);
            const imagePreview = document.getElementById('imagePreviewEdit');
            imagePreview.src = './assets/image/reward/' + rewaImg;
        });
    });

    // เมื่อคลิกปุ่มลบ
    $('.delete-item').click(function() {
        // ดึงข้อมูลจาก data attributes
        var rewaId = $(this).data('rewa-id');
        var rewaName = $(this).data('rewa-name');
        var rewaImg = $(this).data('rewa-img');
        // แสดง SweetAlert2 เพื่อยืนยันการลบ
        Swal.fire({
            title: 'คุณแน่ใจไหม?',
            text: "คุณต้องการลบของรางวัล " + rewaName + " หรือไม่?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ยืนยันลบ',
            cancelButtonText: 'ยกเลิก',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // ถ้าผู้ใช้คลิก "ยืนยันลบ", ส่งข้อมูลไปยังเซิร์ฟเวอร์
                $.ajax({
                    url: './component/savedata/deletereward.php',  // URL ของไฟล์ PHP ที่จะลบข้อมูล
                    type: 'POST',
                    data: {
                        rewa_id: rewaId,
                        rewa_img: rewaImg
                    },
                    success: function(response) {
                        if (response === 'success') {
                            // หากการลบสำเร็จ
                            Swal.fire({
                                title: 'ลบสำเร็จ!',
                                text: 'ของรางวัล ' + rewaName + ' ถูกลบเรียบร้อยแล้ว',
                                icon: 'success'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            // ถ้ามีข้อผิดพลาดในการลบ
                            Swal.fire({
                                title: 'ระบบขัดข้อง',
                                text: 'กรุณาติดต่อผู้ดูแลระบบอีกครั้ง',
                                icon: 'error'
                            });
                        }
                    }
                });
            }
        });
    });
</script>