<?php
require_once 'models/AdminUserModel.php';

class ApiAdminUserController extends BaseApiController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminUserModel();
    }

    // URL: http://localhost/shop_ban_hang/api/adminUser/index

    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';
        $currentUserId = $_GET['current_user_id'] ?? 0;

        if ($keyword) {
            $users = $this->model->searchUsers($keyword, $currentUserId);
        } else {
            $users = $this->model->getAllUsers($currentUserId);
        }
        $this->respond(200, "Thành công", $users);
    }

    // URL: http://localhost/shop_ban_hang/api/adminUser/update_status

    public function update_status()
    {
        $data = $this->getJsonPayload();
        if (isset($data['id'], $data['status'])) {
            $this->model->updateStatus($data['id'], $data['status']);
            $this->respond(200, "Cập nhật thành công");
        }
        $this->respond(400, "Thiếu id hoặc status");
    }

    // URL: http://localhost/shop_ban_hang/api/adminUser/update_role

    public function update_role()
    {
        $data = $this->getJsonPayload();
        if (isset($data['id'], $data['role'])) {
            $success = $this->model->updateRole($data['id'], $data['role']);
            if ($success) $this->respond(200, "Cập nhật thành công");
            $this->respond(500, "Lỗi cập nhật role");
        }
        $this->respond(400, "Thiếu id hoặc role");
    }
}
