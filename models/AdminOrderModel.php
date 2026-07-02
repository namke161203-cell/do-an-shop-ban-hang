<?php
// Gọi file kết nối nếu chưa có (đề phòng lỗi class Database not found)
require_once 'config/db.php'; 

class AdminOrderModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAllOrders() {
        $sql = "SELECT orders.*, users.phone as customer_phone, users.fullname as customer_name
                FROM orders 
                LEFT JOIN users ON orders.user_id = users.id 
                ORDER BY orders.created_at DESC";
                
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($id) {
        // Lấy thông tin đơn hàng + Số điện thoại khách từ bảng users
        $sql = "SELECT orders.*, users.phone as customer_phone, users.fullname as customer_name
                FROM orders 
                LEFT JOIN users ON orders.user_id = users.id 
                WHERE orders.id = ?";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderDetails($orderId) {
        $sql = "SELECT od.*, p.name, p.image 
                FROM order_details od 
                JOIN products p ON od.product_id = p.id 
                WHERE od.order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchOrders($keyword) {
    // 1. Chuẩn bị từ khóa cho TÊN (Dùng LIKE để tìm gần đúng)
    $searchName = "%" . $keyword . "%";
    $sql = "SELECT orders.*, users.phone as customer_phone, users.fullname as customer_name
            FROM orders 
            LEFT JOIN users ON orders.user_id = users.id 
            WHERE orders.id = ? 
               OR users.phone = ? 
               OR orders.phone = ?
               OR users.fullname LIKE ? 
            ORDER BY orders.created_at DESC";
            
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$keyword, $keyword, $keyword, $searchName]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function updateStatus($orderId, $newStatus) {
  
        $stmt = $this->conn->prepare("SELECT status FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $currentStatus = $stmt->fetchColumn();

        if ($currentStatus == $newStatus) return true;

        try {
            $this->conn->beginTransaction();

            if ($newStatus == 'cancelled' && $currentStatus != 'cancelled') {

                $items = $this->getOrderDetails($orderId);

                $sqlRestock = "UPDATE product_variants SET stock = stock + ? WHERE product_id = ? AND size = ?";
                $stmtRestock = $this->conn->prepare($sqlRestock);

                foreach ($items as $item) {
                    $stmtRestock->execute([
                        $item['quantity'],   // Cộng lại số lượng
                        $item['product_id'], // ID sản phẩm
                        $item['size']        // Size tương ứng
                    ]);
                }
            }

            $sqlUpdate = "UPDATE orders SET status = ? WHERE id = ?";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->execute([$newStatus, $orderId]);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>