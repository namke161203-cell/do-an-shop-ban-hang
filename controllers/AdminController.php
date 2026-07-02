<?php
require_once 'config/db.php';
// Nhớ require file Model mới tạo
require_once 'models/AdminDashboardModel.php'; 

class AdminController {
    private $model;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=user&action=login");
            exit;
        }

        // 2. Kiểm tra quyền Admin (Chặt chẽ)
        if ($_SESSION['user']['role'] != 'admin') {
            header("Location: index.php"); 
            exit;
        }

        // 3. Khởi tạo Model (Thay vì kết nối DB trực tiếp)
        $this->model = new AdminDashboardModel();
    }

    public function dashboard() {
        // Gọi các hàm từ Model để lấy dữ liệu
        // Code bây giờ rất dễ đọc, giống như liệt kê công việc
        
        $revenue       = $this->model->getTotalRevenue();
        $newOrders     = $this->model->getPendingOrdersCount();
        $totalProducts = $this->model->getTotalProductsCount();
        $chartData     = $this->model->getRevenueChartData();
        $topProducts   = $this->model->getTopSellingProducts();

        // Gửi dữ liệu sang View
        require_once 'views/admin/dashboard.php';
    }
}
?>