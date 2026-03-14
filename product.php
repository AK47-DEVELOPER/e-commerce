<?php
session_start();
include('./component/connectdatabase.php');

// ดึงข้อมูลสินค้าทั้งหมด
$products = $product_obj->getAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการสินค้า</title>
    <link rel="icon" href="./assets/image/icon.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://code.jquery.com/jquery-3.7.1.js' integrity='sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=' crossorigin='anonymous'></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet" />
</head>
<body>
    <?php 
    include('./component/navbar.php'); 
?>

    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-4 px-0">
                <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                <li class="breadcrumb-item active" aria-current="page">รายการสินค้า</li>
            </ol>
        </nav>
        
        <h1 class="mb-4">รายการสินค้า</h1>

        <!-- การแสดงผลรายการสินค้าทั้งหมด -->
        <div class="row">
            <?php foreach ($products as $product) { ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 rounded-3">
                        <img src="./assets/image/product/<?= htmlspecialchars($product["image"]); ?>" 
                             class="card-img-top" 
                             alt="Product" 
                             style="width: 100%; height: 220px; object-fit: contain; background: #fff; padding: 10px; border-bottom: 1px solid #eee;">
                        <div class="card-body text-center">
                            <h5 class="fw-bold mb-2"><?= htmlspecialchars($product['name']); ?></h5>
                            <p class="text-success fw-semibold mb-1">ราคา <?= number_format($product['price']) ?> บาท</p>
                            <p class="text-muted small mb-3">มีสินค้าทั้งหมด: <?= $product['stock'] ?></p>
                            <a href="product_detail.php?prod_id=<?= $product['id'] ?>" class="btn btn-primary w-100">ดูรายละเอียด</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

</body>
</html>