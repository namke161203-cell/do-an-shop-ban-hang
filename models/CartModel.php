<?php
class CartModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // 1. Lưu giỏ hàng từ Session vào Database (Đồng bộ)
    public function syncCart($userId, $cartItems) {
        // Chiến thuật: Xóa hết cái cũ của user này -> Thêm mới lại toàn bộ (Cách dễ nhất)
        
        // A. Xóa cũ
        $stmt = $this->conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $stmt->execute([$userId]);

        // B. Thêm mới từ Session
        if (!empty($cartItems)) {
            $sql = "INSERT INTO cart_items (user_id, product_id, quantity, size) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            
            foreach ($cartItems as $item) {
                $stmt->execute([$userId, $item['id'], $item['quantity'], $item['size']]);
            }
        }
    }

    // 2. Lấy giỏ hàng từ Database khi Đăng nhập
    public function getCartByUserId($userId) {
        $sql = "SELECT c.product_id as id, c.quantity, c.size, p.name, p.image, p.price 
                FROM cart_items c
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>