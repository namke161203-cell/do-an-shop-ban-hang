<?php
require_once 'models/AdminStockModel.php';

class ApiAdminStockController extends BaseApiController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminStockModel();
    }

    // URL: http://localhost/shop_ban_hang/api/adminStock/brands

    public function brands()
    {
        $brands = $this->model->getAllBrands();
        $this->respond(200, "Thành công", $brands);
    }

    // URL: http://localhost/shop_ban_hang/api/adminStock/products_by_brand

    public function products_by_brand()
    {
        $brandId = $_GET['brand_id'] ?? null;
        if ($brandId) {
            $products = $this->model->getProductsByBrand($brandId);
            $this->respond(200, "Thành công", $products);
        }
        $this->respond(400, "Thiếu brand_id");
    }

    // URL: http://localhost/shop_ban_hang/api/adminStock/import

    public function import()
    {
        $data = $this->getJsonPayload();
        if (isset($data['product_id'], $data['size'], $data['quantity'], $data['import_price'])) {
            $success = $this->model->importStock($data['product_id'], $data['size'], $data['quantity'], $data['import_price']);
            if ($success) $this->respond(200, "Nhập kho thành công");
            $this->respond(500, "Lỗi nhập kho");
        }
        $this->respond(400, "Thiếu dữ liệu nhập kho");
    }

    // URL: http://localhost/shop_ban_hang/api/adminStock/history

    public function history()
    {
        $keyword = $_GET['keyword'] ?? '';
        if ($keyword) {
            $history = $this->model->searchHistory($keyword);
        } else {
            $history = $this->model->getHistory();
        }
        $this->respond(200, "Thành công", $history);
    }

    // URL: http://localhost/shop_ban_hang/api/adminStock/history_detail

    public function history_detail()
    {
        $createdAt = $_GET['created_at'] ?? null;
        if ($createdAt) {
            $details = $this->model->getHistoryDetail($createdAt);
            $this->respond(200, "Thành công", $details);
        }
        $this->respond(400, "Thiếu created_at");
    }

    // URL: http://localhost/shop_ban_hang/api/adminStock/all_import_items

    public function all_import_items()
    {
        $items = $this->model->getAllImportItems();
        $this->respond(200, "Thành công", $items);
    }
}
