<?php
require_once 'models/AdminStockModel.php';

class AdminStockController {
    private $model;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Kiểm tra quyền Admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header("Location: index.php"); exit;
        }
        $this->model = new AdminStockModel();
    }

    public function index() {
        $brands = $this->model->getAllBrands();
        require_once 'views/admin/stock/import.php';
    }

    public function getProductsAjax() {
        if (isset($_GET['brand_id'])) {
            $products = $this->model->getProductsByBrand($_GET['brand_id']);
            echo json_encode($products);
            exit;
        }
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $p_ids = $_POST['product_id'];
            $sizes = $_POST['size'];
            $qtys = $_POST['quantity'];
            $prices = $_POST['import_price'];

            $count = 0;

            for ($i = 0; $i < count($p_ids); $i++) {
                if (!empty($p_ids[$i]) && !empty($sizes[$i]) && $qtys[$i] > 0) {
                    $this->model->importStock($p_ids[$i], $sizes[$i], $qtys[$i], $prices[$i]);
                    $count++;
                }
            }
            
            echo "<script>alert('Đã nhập kho thành công $count dòng!'); window.location.href='index.php?controller=adminStock&action=index';</script>";
        }
    }
    public function history() {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
             $keyword = trim($_GET['keyword']);
             $imports = $this->model->searchHistory($keyword);
        } else {
             $imports = $this->model->getHistory();
        }
        require_once 'views/admin/stock/history.php';
    }

    public function detail() {
        if (isset($_GET['time'])) {
            $time = urldecode($_GET['time']);
            $items = $this->model->getHistoryDetail($time);
            require_once 'views/admin/stock/detail.php';
        } else {
            header("Location: index.php?controller=adminStock&action=history");
        }
    }

    public function export() {
        $data = $this->model->getAllImportItems();
        
        $filename = "Bao_cao_nhap_kho_" . date('Y-m-d_H-i') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF"); // BOM cho tiếng Việt

        // Tiêu đề cột
        fputcsv($output, ['Ngày nhập', 'Mã Phiếu', 'Thương hiệu', 'Sản phẩm', 'Size', 'Số lượng', 'Giá nhập', 'Thành tiền']);

        foreach ($data as $row) {
            $importTime = date('H:i d/m/Y', strtotime($row['created_at']));
            $invoiceCode = 'NK-' . date('ymdHi', strtotime($row['created_at'])); // Tạo mã phiếu giả lập
            $total = $row['quantity'] * $row['import_price'];

            fputcsv($output, [
                $importTime,
                $invoiceCode,
                $row['brand_name'] ?? 'N/A',
                $row['name'],
                $row['size'],
                $row['quantity'],
                number_format($row['import_price'], 0, '', '.'),
                number_format($total, 0, '', '.')
            ]);
        }
        fclose($output);
        exit();
    }
}
?>