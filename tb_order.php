<?php
    session_start();
    if ($_SESSION['permission'] != 'admin') {
        header('Location: index.php');
    }
    $activesidebar = 4;
    $namepage = "ข้อมูลคำสั่งซื้อ";
    $user_fullname = $_SESSION['user_fullname'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ข้อมูลคำสั่งซื้อ</title>
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
            <?php include('./component/modal/modalimage.php'); ?>
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
                                            <th style="text-align: center;">รหัสคำสั่งซื้อ</th>
                                            <th style="text-align: center;">ชื่อ - นามสกุล</th>
                                            <th style="text-align: center;">วันที่สั่งซื้อ</th>
                                            <th style="text-align: center;">หลักฐานการโอนเงิน</th>
                                            <th style="text-align: center;">จำนวนสินค้า</th>
                                            <th style="text-align: center;">ยอดเงินทั้งหมด</th>
                                            <th style="text-align: center;">แต้มที่ต้องได้</th>
                                            <th style="text-align: center;">สถานะ</th>
                                            <th style="text-align: center;">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include ('./component/connectdatabase.php');//ตารางข้อมูลคำสั่งซื้อเชื่อม 3 ตาราง: tb_orders, tb_order_lists, tb_usersใช้ GROUP_CONCAT, SUM เพื่อรวมข้อมูลคำสั่งซื้อแต่ละรายการให้อยู่ในแถวเดียว
                                            $sql = '
                                                select 
                                                    tb_orders.orde_id, orde_point, orde_status, orde_slip, tb_users.user_id, 
                                                    group_concat(distinct concat(tb_users.user_fname, " ", tb_users.user_lname)) as user_name, 
                                                    group_concat(distinct date_format(tb_orders.orde_date, "%d/%m/%Y %H:%i:%s")) as order_dates, 
                                                    group_concat(distinct tb_orders.orde_slip) as order_slips, 
                                                    sum(tb_order_lists.prod_qty) as total_quantity,
                                                    sum(tb_order_lists.prod_price * tb_order_lists.prod_qty) as total_price, 
                                                    group_concat(distinct tb_orders.orde_status) as order_status
                                                FROM tb_orders 
                                                inner join tb_order_lists on tb_orders.orde_id = tb_order_lists.orde_id 
                                                inner join tb_users on tb_orders.user_id = tb_users.user_id 
                                                group by tb_orders.orde_id 
                                                order by tb_orders.orde_id asc';

                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $row["orde_id"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["user_name"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["order_dates"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["order_slips"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["total_quantity"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["total_price"]; ?></td>
                                            <td style="text-align: center;"><?php echo $row["orde_point"]; ?></td>
                                            <td style="text-align: center;">
                                                <?php if ($row["orde_status"] === 'รออนุมัติคำสั่งซื้อ') { ?>
                                                <button style="background-color: #28a745; color: white; border: 1px solid #28a745; border-radius: 5px; font-size: 14px; cursor: pointer; transition: background-color 0.3s ease, transform 0.3s ease;" onclick="confirmorder('คำสั่งซื้อสำเร็จ', <?php echo $row['orde_id']; ?>, <?php echo $row['orde_point']; ?>, <?php echo $row['user_id']; ?>)">อนุมัติ</button>
                                                <button style="background-color: #dc3545; color: white; border: 1px solid #dc3545; border-radius: 5px; font-size: 14px; cursor: pointer; transition: background-color 0.3s ease, transform 0.3s ease;" onclick="confirmorder('ยกเลิกคำสั่งซื้อ', <?php echo $row['orde_id']; ?>, <?php echo $row['orde_point']; ?>, <?php echo $row['user_id']; ?>)">ไม่อนุมัติ</button>
                                                <?php
                                                    } else if ($row["orde_status"] === 'คำสั่งซื้อสำเร็จ') {
                                                        echo "<span style='color: #28a745'>" . $row["orde_status"] . "</span>";
                                                    } else {
                                                        echo "<span style='color: #dc3545'>" . $row["orde_status"] . "</span>";
                                                    }
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <a class="text-danger view-item" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-url="<?php echo $row["orde_slip"]; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
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

        
    });

    const viewItemButtons = document.querySelectorAll('.view-item');
    const modalImage = document.getElementById('modalImage');
    viewItemButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const imageUrl = event.currentTarget.getAttribute('data-image-url');
            modalImage.src = './assets/image/slip/' + imageUrl;  // ตั้งค่าลิงก์ที่เหมาะสมของรูป
        });
    });

    function confirmorder(status, orderid, point, userid) { // ยืนยันคำสั่งซื้อ
        $.ajax({
            url: './component/savedata/confirmorder.php',  // URL ของไฟล์ PHP ที่จะลบข้อมูล
            type: 'POST',
            data: {
                orderid: orderid,
                status: status,
                point: point,
                userid: userid
            },
            success: function(response) {
                if (response === 'success') {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'ทำรายการสำเร็จ',
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'ระบบขัดข้อง',
                        text: 'กรุณาติดต่อผู้ดูแลระบบอีกครั้ง',
                        icon: 'error'
                    });
                }
            }
        });
    }
</script>