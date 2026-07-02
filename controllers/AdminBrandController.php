<?php
require_once 'models/AdminBrandModel.php';

class AdminBrandController {
    private $model;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header("Location: index.php"); exit;
        }
        $this->model = new AdminBrandModel();
    }

    public function index() {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
             $keyword = trim($_GET['keyword']);
             $brands = $this->model->searchBrands($keyword);
        } else {
             $brands = $this->model->getAll();
        }
        require_once 'views/admin/brands/index.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            if (!empty($name)) {
                $this->model->insert($name);
            }
            header("Location: index.php?controller=adminBrand&action=index");
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->model->delete($_GET['id']);
        }
        header("Location: index.php?controller=adminBrand&action=index");
    }
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $this->model->update($id, $name);
            header("Location: index.php?controller=adminBrand&action=index");
        }
    }

    public function showProducts() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $brand = $this->model->getById($id);
            $products = $this->model->getProductsByBrandId($id);
            require_once 'views/admin/brands/products.php';
        } else {

            header("Location: index.php?controller=adminBrand&action=index");
        }
    }
    public function export() {

        $brands = $this->model->getAll(); 
        $filename = "Danh_sach_thuong_hieu_" . date('Y-m-d_H-i') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF");
        fputcsv($output, ['ID', 'Tên thương hiệu']);

        foreach ($brands as $row) {
            fputcsv($output, [
                $row['id'],
                $row['name']
            ]);
        }
        fclose($output);
        exit();
    }
}
?>