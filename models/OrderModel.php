<?php
class OrderModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function createOrder($userId, $customerData, $cartItems) {
        try {
            // 1. Bắt đầu transaction (Đảm bảo dữ liệu nhất quán)
            $this->conn->beginTransaction();

            // 2. Lưu thông tin chung vào bảng `orders`
            // --- SỬA Ở ĐÂY: Thêm cột payment_method vào câu lệnh SQL ---
            $sqlOrder = "INSERT INTO orders (user_id, fullname, phone, address, note, total_money, payment_method, status, created_at) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
            
            $stmt = $this->conn->prepare($sqlOrder);
            
            // --- SỬA Ở ĐÂY: Thêm biến vào mảng execute (Lưu ý thứ tự phải khớp với SQL trên) ---
            $stmt->execute([
                $userId,
                $customerData['fullname'],
                $customerData['phone'],
                $customerData['address'],
                $customerData['note'],
                $customerData['total_money'],
                $customerData['payment_method'] // <--- QUAN TRỌNG: Dòng mới thêm
            ]);
            
            // Lấy ID của đơn hàng vừa tạo
            $orderId = $this->conn->lastInsertId();

            // 3. Lưu chi tiết từng món vào bảng `order_details`
            $sqlDetail = "INSERT INTO order_details (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)";
            $stmtDetail = $this->conn->prepare($sqlDetail);

            foreach ($cartItems as $item) {
                $stmtDetail->execute([
                    $orderId,
                    $item['id'],
                    $item['size'],
                    $item['quantity'],
                    $item['price']
                ]);
                
                // --- BỎ COMMENT ĐOẠN NÀY ĐỂ TRỪ KHO ---
                // Cập nhật số lượng tồn kho: Stock = Stock - Quantity
                $sqlUpdateStock = "UPDATE product_variants SET stock = stock - ? WHERE product_id = ? AND size = ?";
                $stmtStock = $this->conn->prepare($sqlUpdateStock);
                
                // Lưu ý: Đảm bảo bảng 'product_variants' của bạn có cột 'stock', 'product_id', 'size'
                $stmtStock->execute([$item['quantity'], $item['id'], $item['size']]);
            }

            // 4. Xác nhận mọi thứ thành công
            $this->conn->commit();
            return $orderId;

        } catch (Exception $e) {
            // Nếu có lỗi, hủy toàn bộ thao tác
            $this->conn->rollBack();
            return "Lỗi DB: " . $e->getMessage();
        }
    }
    public function getOrdersByUserId($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // (Tuỳ chọn) Hàm lấy chi tiết đơn hàng để xem cụ thể sau này
    public function getOrderDetail($orderId) {
        $sql = "SELECT od.*, p.name, p.image 
                FROM order_details od 
                JOIN products p ON od.product_id = p.id 
                WHERE od.order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // 1. Lấy chi tiết 1 đơn hàng (để hiển thị form sửa)
    public function getOrderById($orderId) {
        $sql = "SELECT * FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Hủy đơn hàng (Chỉ cho hủy nếu là chủ đơn + trạng thái pending)
    public function cancelOrder($orderId, $userId) {
        $sql = "UPDATE orders SET status = 'cancelled' 
                WHERE id = ? AND user_id = ? AND status = 'pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderId, $userId]);
        // Trả về số dòng bị ảnh hưởng (nếu > 0 tức là hủy thành công)
        return $stmt->rowCount() > 0;
    }

    // 3. Cập nhật địa chỉ & SĐT
    public function updateOrderInfo($orderId, $userId, $phone, $address) {
        $sql = "UPDATE orders SET phone = ?, address = ? 
                WHERE id = ? AND user_id = ? AND status = 'pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$phone, $address, $orderId, $userId]);
        return $stmt->rowCount() > 0;
    }
}
?>