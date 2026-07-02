<?php
require_once 'models/AdminProductModel.php';

class AdminProductController {
    private $model;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
                header("Location: index.php");
            exit;
        }
        $this->model = new AdminProductModel();
    }

    public function index() {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $keyword = trim($_GET['keyword']);
            $products = $this->model->searchProducts($keyword);
        } else {
            $products = $this->model->getAll();
        }
        require_once 'views/admin/products/index.php';
    }

    public function create() {  // hiển thị form thêm sp mới
        $brands = $this->model->getBrands();
        $categories = $this->model->getCategories();
        require_once 'views/admin/products/create.php';
    }

    public function store() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $price = $_POST['price'];
            $old_price = $_POST['old_price'] ?? 0;
            $sale_price = $_POST['sale_price'] ?? 0;

            if ($old_price > 0) {
                $compare_price = $old_price;
                $error_msg = 'Lỗi: Giá Flash Sale phải NHỎ HƠN Giá gốc (' . number_format($old_price) . ')!';
            } else {
                $compare_price = $price;
                $error_msg = 'Lỗi: Giá Flash Sale phải NHỎ HƠN Giá bán (' . number_format($price) . ')!';
            }

            if ($sale_price > 0 && $sale_price >= $compare_price) {
                echo "<script>
                        alert('$error_msg'); 
                        history.back(); 
                      </script>";
                return; 
            }
            //Ảnh đại diện chính
            $imagePath = "";
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "assets/uploads/";
                $fileName = time() . "_" . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $fileName;
                
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $imagePath = $target_file;
                }
            }

            $productId = $this->model->insert(
                $_POST['name'],
                $_POST['price'],
                $_POST['old_price'],
                $_POST['brand_id'],
                $_POST['category_id'],
                $imagePath,
                $_POST['description'],
                $_POST['sale_price'] ?? 0,
                $_POST['sale_start'] ?? null,
                $_POST['sale_end'] ?? null
            );

            if ($productId) {
                //Lưu nhiều ảnh (Gallery)
                if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
                    $files = $_FILES['gallery'];
                    $count = count($files['name']);
                    
                    for ($i = 0; $i < $count; $i++) {
                        if ($files['error'][$i] === 0) {
                            $newName = time() . '_' . $i . '_' . basename($files['name'][$i]);
                            $dest = "assets/uploads/" . $newName;
                            
                            if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                                $this->model->addProductImage($productId, $dest);
                            }
                        }
                    }
                }
            }

            header("Location: index.php?controller=adminProduct&action=index");
        }
    }

    public function edit() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $product = $this->model->getById($id); 
            $variants = $this->model->getVariants($id); 
            $gallery = $this->model->getProductImages($id);

            $brands = $this->model->getBrands();
            $categories = $this->model->getCategories();
            
            require_once 'views/admin/products/edit.php';
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];

            $price = $_POST['price'];
            $old_price = $_POST['old_price'];
            $brand_id = $_POST['brand_id'];
            $category_id = $_POST['category_id'];
            $description = $_POST['description'];
            
            $sale_price = $_POST['sale_price'] ?? 0;
            $sale_start = $_POST['sale_start'] ?? null;
            $sale_end = $_POST['sale_end'] ?? null;

            if ($old_price > 0) {
                $compare_price = $old_price;
                $error_msg = 'Lỗi: Giá Flash Sale phải NHỎ HƠN Giá gốc (' . number_format($old_price) . ')!';
            } else {
                $compare_price = $price;
                $error_msg = 'Lỗi: Giá Flash Sale phải NHỎ HƠN Giá bán (' . number_format($price) . ')!';
            }

            if ($sale_price > 0 && $sale_price >= $compare_price) {
                echo "<script>
                        alert('$error_msg'); 
                        history.back(); 
                      </script>";
                return;
            }
            // 1. Handle Main Image
            $imagePath = $_POST['current_image']; 
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "assets/uploads/";
                $fileName = time() . "_" . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $fileName;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $imagePath = $target_file;
                }
            }
            // 2. Update General Info + FLASH SALE
            $this->model->update(
                $id, $name, $price, $old_price, $brand_id, $category_id, 
                $imagePath, $description, 
                $sale_price, $sale_start, $sale_end
            );
            // 3. Handle Gallery Images
            if (isset($_POST['delete_gallery'])) {
                foreach ($_POST['delete_gallery'] as $imgId) {
                    $this->model->deleteProductImageById($imgId);
                }
            }

            if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
                $files = $_FILES['gallery'];
                $count = count($files['name']);
                
                for ($i = 0; $i < $count; $i++) {
                    if ($files['error'][$i] === 0) {
                        $newName = time() . '_' . $i . '_' . basename($files['name'][$i]);
                        $dest = "assets/uploads/" . $newName;
                        
                        if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                            $this->model->addProductImage($id, $dest);
                        }
                    }
                }
            }

            header("Location: index.php?controller=adminProduct&action=index");
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->model->delete($_GET['id']);
        }
        header("Location: index.php?controller=adminProduct&action=index");
    }
    public function export() {
        $products = $this->model->getAll(); 

        $filename = "Danh_sach_san_pham_" . date('Y-m-d_H-i') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF"); // BOM cho tiếng Việt
        fputcsv($output, [
            'STT', 
            'Tên sản phẩm', 
            'Danh mục', 
            'Thương hiệu', 
            'Kho hàng', 
            'Giá bán (Price)',       
            'Giá gốc (Old Price)',   
            'Giá Flash Sale', 
            'Bắt đầu Sale', 
            'Kết thúc Sale'
        ]);
      $stt = 0;
        //Ghi dữ liệu dòng
        foreach ($products as $p) {
            $stt++;
            $variantStr = isset($p['variant_info']) ? str_replace("<br>", ", ", $p['variant_info']) : 'Hết hàng';
            
            fputcsv($output, [
                $stt,
                $p['name'],
                $p['cat_name'] ?? '',
                $p['brand_name'] ?? '',
                $variantStr,
                number_format($p['price'], 0, '', '.'),
                number_format($p['old_price'] ?? 0, 0, '', '.'), 
                number_format($p['sale_price'] ?? 0, 0, '', '.'), 
                $p['sale_start'] ?? '',
                $p['sale_end'] ?? ''
            ]);
        }
        fclose($output);
        exit();
    }
}
?>