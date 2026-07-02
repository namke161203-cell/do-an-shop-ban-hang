<?php
require_once 'models/UserModel.php';

class ApiUserController extends BaseApiController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }

    // URL: http://localhost/shop_ban_hang/api/user/login

    public function login()
    {
        $data = $this->getJsonPayload();
        if (isset($data['email']) && isset($data['password'])) {
            $user = $this->model->login($data['email'], $data['password']);
            if ($user) {
                unset($user['password']);
                $this->respond(200, "Đăng nhập thành công", $user);
            }
            $this->respond(401, "Email hoặc mật khẩu không đúng");
        }
        $this->respond(400, "Thiếu email hoặc mật khẩu");
    }

    // URL: http://localhost/shop_ban_hang/api/user/register

    public function register()
    {
        $data = $this->getJsonPayload();
        if (isset($data['fullname'], $data['email'], $data['password'], $data['phone'], $data['address'])) {
            if ($this->model->checkEmail($data['email'])) {
                $this->respond(409, "Email đã tồn tại");
            }
            $success = $this->model->register($data['fullname'], $data['email'], $data['password'], $data['phone'], $data['address']);
            if ($success) $this->respond(201, "Đăng ký thành công");
            $this->respond(500, "Đăng ký thất bại");
        }
        $this->respond(400, "Thiếu thông tin đăng ký");
    }

    // URL: http://localhost/shop_ban_hang/api/user/profile

    public function profile()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $user = $this->model->getUserById($id);
            if ($user) {
                unset($user['password']);
                $this->respond(200, "Thành công", $user);
            }
            $this->respond(404, "Không tìm thấy user");
        }
        $this->respond(400, "Thiếu user id");
    }

    // URL: http://localhost/shop_ban_hang/api/user/update?id=1

    public function update()
    {
        $data = $this->getJsonPayload();
        if (isset($data['id'], $data['fullname'], $data['phone'], $data['address'])) {
            $success = $this->model->updateProfile($data['id'], $data['fullname'], $data['phone'], $data['address']);
            if ($success) $this->respond(200, "Cập nhật thành công");
            $this->respond(500, "Cập nhật thất bại");
        }
        $this->respond(400, "Thiếu thông tin cần cập nhật");
    }
}
