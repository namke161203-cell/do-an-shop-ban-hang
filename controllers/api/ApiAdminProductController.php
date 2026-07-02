<?php
require_once 'models/AdminProductModel.php';

class ApiAdminProductController extends BaseApiController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminProductModel();
    }

    //  URL: http://localhost/shop_ban_hang/api/adminProduct/index?keyword=1

    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';
        if ($keyword) {
            $products = $this->model->searchProducts($keyword);
        } else {
            $products = $this->model->getAll();
        }
        $this->respond(200, "Thành công", $products);
    }

    // URL: http://localhost/shop_ban_hang/api/adminProduct/detail?id=1

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $product = $this->model->getById($id);
            if ($product) {
                $product['variants'] = $this->model->getVariants($id);
                $product['images'] = $this->model->getProductImages($id);
                $this->respond(200, "Thành công", $product);
            }
            $this->respond(404, "Không tìm thấy sản phẩm");
        }
        $this->respond(400, "Thiếu id");
    }

    // URL: http://localhost/shop_ban_hang/api/adminProduct/create?id=1

    public function create()
    {
        $data = $this->getJsonPayload();

        $name = $data['name'] ?? '';
        $price = $data['price'] ?? 0;
        $old_price = $data['old_price'] ?? 0;
        $brand_id = $data['brand_id'] ?? 0;
        $category_id = $data['category_id'] ?? 0;
        $image = $data['image'] ?? '';
        $desc = $data['description'] ?? '';
        $sale_price = $data['sale_price'] ?? 0;
        $sale_start = $data['sale_start'] ?? null;
        $sale_end = $data['sale_end'] ?? null;

        if (!$name || !$price || !$brand_id || !$category_id) {
            $this->respond(400, "Thiếu thông tin bắt buộc (name, price, brand_id, category_id)");
        }

        if ($sale_price > 0 && $sale_price >= $price) {
            $this->respond(400, "Giá Sale ($sale_price) không được lớn hơn hoặc bằng Giá bán ($price)");
        }

        $productId = $this->model->insert($name, $price, $old_price, $brand_id, $category_id, $image, $desc, $sale_price, $sale_start, $sale_end);

        if ($productId) {
            if (!empty($data['variants']) && is_array($data['variants'])) {
                foreach ($data['variants'] as $v) {
                    if (isset($v['size']) && isset($v['stock'])) {
                        $this->model->insertVariant($productId, $v['size'], $v['stock']);
                    }
                }
            }

            if (!empty($data['gallery']) && is_array($data['gallery'])) {
                foreach ($data['gallery'] as $imgUrl) {
                    $this->model->addProductImage($productId, $imgUrl);
                }
            }

            $this->respond(201, "Tạo sản phẩm thành công", ["id" => $productId]);
        }
        $this->respond(500, "Lỗi tạo sản phẩm");
    }

    // URL: http://localhost/shop_ban_hang/api/adminProduct/update?id=1

    public function update()
    {
        $data = $this->getJsonPayload();

        $id = $data['id'] ?? 0;


        if ($id) {
            $checkExist = $this->model->getById($id);
            if (!$checkExist) {
                $this->respond(404, "Lỗi: Không tìm thấy sản phẩm có ID là $id trong Database!");
            }
        }
        // ---------------------------------------------

        $name = $data['name'] ?? '';
        $price = $data['price'] ?? 0;
        $old_price = $data['old_price'] ?? 0;
        $brand_id = $data['brand_id'] ?? 0;
        $category_id = $data['category_id'] ?? 0;
        $image = $data['image'] ?? '';
        $desc = $data['description'] ?? '';
        $sale_price = $data['sale_price'] ?? 0;
        $sale_start = $data['sale_start'] ?? null;
        $sale_end = $data['sale_end'] ?? null;

        if (!$id || !$name || !$price || !$brand_id || !$category_id) {
            $this->respond(400, "Thiếu thông tin bắt buộc (id, name, price, brand_id, category_id)");
        }

        if ($sale_price > 0 && $sale_price >= $price) {
            $this->respond(400, "Giá Sale ($sale_price) không được lớn hơn hoặc bằng Giá bán ($price)");
        }

        // Thực hiện cập nhật vào DB
        $this->model->update($id, $name, $price, $old_price, $brand_id, $category_id, $image, $desc, $sale_price, $sale_start, $sale_end);

        if (isset($data['variants']) && is_array($data['variants'])) {
            $this->model->deleteVariants($id);
            foreach ($data['variants'] as $v) {
                if (isset($v['size']) && isset($v['stock'])) {
                    $this->model->insertVariant($id, $v['size'], $v['stock']);
                }
            }
        }

        if (isset($data['gallery']) && is_array($data['gallery'])) {
            $this->model->deleteProductImages($id);
            foreach ($data['gallery'] as $imgUrl) {
                $this->model->addProductImage($id, $imgUrl);
            }
        }

        $this->respond(200, "Cập nhật sản phẩm thành công");
    }

    // URL: http://localhost/shop_ban_hang/api/adminProduct/delete?id=1

    public function delete()
    {
        $data = $this->getJsonPayload();
        if (isset($data['id'])) {
            $this->model->delete($data['id']);
            $this->respond(200, "Xoá sản phẩm thành công");
        }
        $this->respond(400, "Thiếu id");
    }

    // URL: http://localhost/shop_ban_hang/api/adminProduct/config_data

    public function config_data()
    {
        $brands = $this->model->getBrands();
        $categories = $this->model->getCategories();
        $this->respond(200, "Thành công", ["brands" => $brands, "categories" => $categories]);
    }
}
