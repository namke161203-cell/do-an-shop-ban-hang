<?php
require_once 'models/AdminBrandModel.php';

class ApiAdminBrandController extends BaseApiController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminBrandModel();
    }

    // 👉 URL: http://localhost/shop_ban_hang/api/adminBrand/index

    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';
        if ($keyword) {
            $brands = $this->model->searchBrands($keyword);
        } else {
            $brands = $this->model->getAll();
        }
        $this->respond(200, "Thành công", $brands);
    }

    // URL: http://localhost/shop_ban_hang/api/adminBrand/detail?id=1

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $brand = $this->model->getById($id);
            if ($brand) $this->respond(200, "Thành công", $brand);
            $this->respond(404, "Không tìm thấy brand");
        }
        $this->respond(400, "Thiếu id");
    }

    // URL: http://localhost/shop_ban_hang/api/adminBrand/create

    public function create()
    {
        $data = $this->getJsonPayload();
        if (!empty($data['name'])) {
            $success = $this->model->insert($data['name']);
            if ($success) $this->respond(201, "Tạo thành công");
            $this->respond(500, "Tạo thất bại");
        }
        $this->respond(400, "Thiếu name");
    }

    // URL: http://localhost/shop_ban_hang/api/adminBrand/update?id=1

    public function update()
    {
        $data = $this->getJsonPayload();
        if (isset($data['id'], $data['name'])) {
            $success = $this->model->update($data['id'], $data['name']);
            if ($success) $this->respond(200, "Cập nhật thành công");
            $this->respond(500, "Cập nhật thất bại");
        }
        $this->respond(400, "Thiếu id hoặc name");
    }

    // URL: http://localhost/shop_ban_hang/api/adminBrand/delete?id=1

    public function delete()
    {
        $data = $this->getJsonPayload();
        $id = $data['id'] ?? null;
        if ($id) {
            $success = $this->model->delete($id);
            if ($success) $this->respond(200, "Xoá thành công");
            $this->respond(500, "Xoá thất bại");
        }
        $this->respond(400, "Thiếu id");
    }
}
