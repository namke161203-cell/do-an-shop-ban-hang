<?php
require_once 'models/ProductModel.php';

class HomeController {
    public function index() {
        $productModel = new ProductModel();
        $flashSales = $productModel->getFlashSaleProducts();       
        $newProducts = $productModel->getProducts('', null, 'newest', 8, 0);
        require_once 'views/home/index.php';
    }
}
?>