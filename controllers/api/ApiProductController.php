<?php
require_once 'models/ProductModel.php';

class ApiProductController extends BaseApiController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new ProductModel();
    }

    // URL: http://localhost/shop_ban_hang/api/product/index

    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';
        $categoryId = $_GET['category_id'] ?? null;
        $sort = $_GET['sort'] ?? 'newest';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 9;
        $offset = ($page - 1) * $limit;

        $products = $this->model->getProducts($keyword, $categoryId, $sort, $limit, $offset);
        $total = $this->model->countTotal($keyword, $categoryId);

        if ($products) {
            $extraData = [
                "total" => $total,
                "total_pages" => ceil($total / $limit),
                "current_page" => $page
            ];
            $this->respond(200, "Thành công", $products, $extraData);
        } else {
            $this->respond(404, "Không tìm thấy sản phẩm nào");
        }
    }

    // URL: http://localhost/shop_ban_hang/api/product/detail

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $product = $this->model->getProductDetail($id);
            if ($product) {
                $images = $this->model->getProductImages($id);
                $product['gallery'] = $images;
                $this->respond(200, "Thành công", $product);
            }
            $this->respond(404, "Sản phẩm không tồn tại");
        }
        $this->respond(400, "Vui lòng cung cấp ID sản phẩm");
    }

    // URL: http://localhost/shop_ban_hang/api/product/related

    public function related()
    {
        $categoryId = $_GET['category_id'] ?? null;
        $currentId = $_GET['current_id'] ?? null;
        if ($categoryId && $currentId) {
            $products = $this->model->getRelatedProducts($categoryId, $currentId);
            $this->respond(200, "Thành công", $products);
        }
        $this->respond(400, "Thiếu category_id hoặc current_id");
    }

    // URL: http://localhost/shop_ban_hang/api/product/flash_sale

    public function flash_sale()
    {
        $products = $this->model->getFlashSaleProducts();
        $this->respond(200, "Thành công", $products);
    }

    // URL: http://localhost/shop_ban_hang/api/product/view

    public function view()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            // Tăng lượt xem trong DB
            $success = $this->model->incrementView($id);
            if ($success) {
                $this->respond(200, "Đã tăng lượt xem thành công", ["id" => $id]);
            } else {
                $this->respond(500, "Lỗi khi cập nhật lượt xem");
            }
        } else {
            $this->respond(400, "Vui lòng cung cấp ID sản phẩm (id)");
        }
    }
}
