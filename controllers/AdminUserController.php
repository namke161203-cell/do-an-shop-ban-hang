<?php
require_once 'models/AdminUserModel.php';

class AdminUserController {
    private $model;

    public function __construct() {

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            die("Bạn không có quyền truy cập khu vực này!");
        }
        $this->model = new AdminUserModel();
    }

    public function index() {
        $currentUserId = $_SESSION['user']['id'];
        
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
             $keyword = trim($_GET['keyword']);
             $users = $this->model->searchUsers($keyword, $currentUserId);
        } else {
             $users = $this->model->getAllUsers($currentUserId);
        }
        
        require_once 'views/admin/users/index.php';
    }

    public function lock() {
        if (isset($_GET['id'])) {
            $this->model->updateStatus($_GET['id'], 0);
        }
        header("Location: index.php?controller=adminUser&action=index");
    }

    public function unlock() {
        if (isset($_GET['id'])) {
            $this->model->updateStatus($_GET['id'], 1);
        }
        header("Location: index.php?controller=adminUser&action=index");
    }
    public function update_role() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['user_id'];
            $role = $_POST['role'];


            $this->model->updateRole($id, $role);

            header("Location: index.php?controller=adminUser&action=index");
        }
    }
}
?>