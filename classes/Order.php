<?php

class Order {
    private $conn;
    private $table_orders = "orders";
    private $table_items = "order_items";

    public function __construct($db) {
        $this->conn = $db;
    }

    // สร้างคำสั่งซื้อ (Transaction)
    public function checkout($user_id, $cart_items, $total_amount) {
        try {
            // เริ่ม Transaction
            $this->conn->beginTransaction();

            // 1. ตรวจสอบสต๊อกอีกครั้งเพื่อความแน่ใจ (Double Check)
            foreach ($cart_items as $item) {
                $check_stmt = $this->conn->prepare("SELECT stock FROM products WHERE id = :pid FOR UPDATE");
                $check_stmt->bindParam(':pid', $item['product_id']);
                $check_stmt->execute();
                $product = $check_stmt->fetch();

                if (!$product || $product['stock'] < $item['quantity']) {
                    throw new Exception("สินค้า {$item['name']} มีสต๊อกไม่เพียงพอ");
                }
            }

            // 2. Insert ลง orders table
            $query_order = "INSERT INTO " . $this->table_orders . " (user_id, total_amount, status, created_at) 
                            VALUES (:uid, :amount, '0', NOW())";
            $stmt_order = $this->conn->prepare($query_order);
            $stmt_order->bindParam(':uid', $user_id);
            $stmt_order->bindParam(':amount', $total_amount);
            $stmt_order->execute();
            
            $order_id = $this->conn->lastInsertId();

            // 3. Insert ลง order_items และตัดสต๊อก
            $query_item = "INSERT INTO " . $this->table_items . " (order_id, product_id, price, quantity) 
                           VALUES (:oid, :pid, :price, :qty)";
            $stmt_item = $this->conn->prepare($query_item);

            $query_stock = "UPDATE products SET stock = stock - :qty, sales_total = sales_total + :qty WHERE id = :pid";
            $stmt_stock = $this->conn->prepare($query_stock);

            foreach ($cart_items as $item) {
                // Insert Item
                $stmt_item->bindParam(':oid', $order_id);
                $stmt_item->bindParam(':pid', $item['product_id']);
                $stmt_item->bindParam(':price', $item['price']);
                $stmt_item->bindParam(':qty', $item['quantity']);
                $stmt_item->execute();

                // ตัดสต๊อกและเพิ่มยอขาย
                $stmt_stock->bindParam(':qty', $item['quantity']);
                $stmt_stock->bindParam(':pid', $item['product_id']);
                $stmt_stock->execute();
            }

            // 4. ล้างตะกร้า
            $query_clear = "DELETE FROM cart WHERE user_id = :uid";
            $stmt_clear = $this->conn->prepare($query_clear);
            $stmt_clear->bindParam(':uid', $user_id);
            $stmt_clear->execute();

            // Commit Transaction ถ้าทุกอย่างผ่าน
            $this->conn->commit();
            return ["success" => true, "order_id" => $order_id];

        } catch (Exception $e) {
            // มีข้อผิดพลาด ย้อนกลับข้อมูลทั้งหมด
            $this->conn->rollBack();
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // ดึงประวัติคำสั่งซื้อของ User
    public function getUserOrders($user_id) {
        $query = "SELECT * FROM " . $this->table_orders . " WHERE user_id = :uid ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uid', $user_id);
        $stmt->execute();
        $orders = $stmt->fetchAll();

        // ดึง items ของแต่ละ order
        foreach ($orders as &$order) {
            $query_items = "SELECT i.*, p.name, p.image 
                            FROM " . $this->table_items . " i 
                            JOIN products p ON i.product_id = p.id 
                            WHERE i.order_id = :oid";
            $stmt_items = $this->conn->prepare($query_items);
            $stmt_items->bindParam(':oid', $order['id']);
            $stmt_items->execute();
            $order['items'] = $stmt_items->fetchAll();
        }

        return $orders;
    }

    // ดึงข้อมูลคำสั่งซื้อและผู้ใช้สำหรับหน้าต่างชำระเงิน
    public function getOrderDetails($order_id, $user_id) {
         $query = "SELECT * FROM " . $this->table_orders . " WHERE id = :oid AND user_id = :uid LIMIT 1";
         $stmt = $this->conn->prepare($query);
         $stmt->bindParam(':oid', $order_id);
         $stmt->bindParam(':uid', $user_id);
         $stmt->execute();
         return $stmt->fetch();
    }

    // อัปโหลดสลิปยืนยันการชำระเงิน (Secure Upload)
    public function confirmPayment($order_id, $user_id, $bank_name, $transfer_date, $transfer_time, $file) {
        // Validation สลิป
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return "ไม่พบไฟล์สลิป";
        }

        // ตรวจสอบ MIME type จริงๆ (ป้องกันแก้สกุลไฟล์แล้วหลอกอัปโหลด shell)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowed_types)) {
            return "อนุญาตเฉพาะไฟล์รูปภาพ (JPG, PNG) เท่านั้น";
        }

        if ($file['size'] > $max_size) {
            return "ไฟล์มีขนาดใหญ่เกินไป (ห้ามเกิน 5MB)";
        }

        $upload_dir = __DIR__ . "/../assets/image/slip/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // สร้างชื่อไฟล์ใหม่เพื่อป้องกันชื่อซ้ำและอักขระแปลกประหลาด
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = "slip_" . $order_id . "_" . time() . "." . $ext;
        $target_file = $upload_dir . $new_filename;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // อัปเดตฐานข้อมูล
            $query = "UPDATE " . $this->table_orders . " 
                      SET slip_image = :slip, bank_name = :bank, transfer_date = :tdate, transfer_time = :ttime, status = '1' 
                      WHERE id = :oid AND user_id = :uid";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':slip', $new_filename);
            $stmt->bindParam(':bank', $bank_name);
            $stmt->bindParam(':tdate', $transfer_date);
            $stmt->bindParam(':ttime', $transfer_time);
            $stmt->bindParam(':oid', $order_id);
            $stmt->bindParam(':uid', $user_id);

            if($stmt->execute()){
                return true;
            } else {
                return "บันทึกข้อมูลล้มเหลว";
            }
        }

        return "อัปโหลดไฟล์ไม่สำเร็จ";
    }
}
?>
