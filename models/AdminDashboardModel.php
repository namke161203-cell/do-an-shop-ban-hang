<?php
class AdminDashboardModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    //Lấy tổng doanh thu (đơn đã hoàn thành)
    public function getTotalRevenue() {
        $sql = "SELECT SUM(total_money) as revenue FROM orders WHERE status = 'completed'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['revenue'] ?? 0;
    }

    //Đếm số đơn hàng mới (Pending)
    public function getPendingOrdersCount() {
        $sql = "SELECT COUNT(*) as count FROM orders WHERE status = 'pending'";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchColumn();
    }

    //Đếm tổng số sản phẩm
    public function getTotalProductsCount() {
        $sql = "SELECT COUNT(*) as count FROM products";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchColumn();
    }
    //dữ liệu biểu đồ 6 tháng
    public function getRevenueChartData() {
        $sql = "SELECT DATE_FORMAT(created_at, '%m-%Y') as month, SUM(total_money) as total 
                FROM orders 
                WHERE status = 'completed' 
                GROUP BY DATE_FORMAT(created_at, '%m-%Y') 
                ORDER BY created_at DESC LIMIT 6";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Xử lý đảo ngược mảng ngay tại Model để Controller nhận dữ liệu đã sẵn sàng
        return array_reverse($data);
    }
    //  sản phẩm bán chạy
    public function getTopSellingProducts() {
        $sql = "SELECT p.name, p.image, SUM(od.quantity) as total_sold 
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                JOIN orders o ON od.order_id = o.id
                WHERE o.status = 'completed' 
                GROUP BY p.id 
                ORDER BY total_sold DESC 
                LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>