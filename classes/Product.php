<?php

class Product {
    private $conn;
    private $table_name = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ดึงสินค้าทั้งหมด
    public function getAll($limit = null) {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        if ($limit) {
            $query .= " LIMIT " . (int)$limit;
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ดึงสินค้า Top 10 ขายดี
    public function getTopSelling($limit = 10) {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY sales_total DESC LIMIT " . (int)$limit;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ดึงสินค้าใหม่
    public function getNewest($limit = 10) {
        return $this->getAll($limit);
    }

    // ดึงรายละเอียดสินค้าจาก ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ดึงสินค้าสำหรับ Carousel
    public function getForCarousel($limit = 6) {
        return $this->getAll($limit);
    }
}
?>
