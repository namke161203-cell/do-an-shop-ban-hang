<?php
require_once 'models/ProductModel.php';

class ProductController {

    public function list() {
        $model = new ProductModel();

        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $categoryId = isset($_GET['category']) ? $_GET['category'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $limit = 6; 
        $offset = ($page - 1) * $limit;

        $products = $model->getProducts($keyword, $categoryId, $sort, $limit, $offset);
        $totalProducts = $model->countTotal($keyword, $categoryId);
        $totalPages = ceil($totalProducts / $limit);

        require_once 'views/products/list.php';
    }

    public function detail() {

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header("Location: index.php");
            exit;
        }

        $id = intval($_GET['id']); 
        $model = new ProductModel();

        $product = $model->getProductDetail($id);
        if (!$product) {

            header("Location: index.php");
            exit;
        }

        $secondaryImages = $model->getProductImages($id);

        $relatedProducts = $model->getRelatedProducts($product['category_id'], $id); 

        require_once 'views/products/detail.php';
    }

    public function search() {
        $this->list(); 
    }
    
}
?>