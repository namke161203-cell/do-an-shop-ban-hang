<?php
require_once 'models/AdminOrderModel.php';

class ApiAdminOrderController extends BaseApiController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminOrderModel();
    }

    // URL: http://localhost/shop_ban_hang/api/adminOrder/index?keyword=1

    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';
        if ($keyword) {
            $orders = $this->model->searchOrders($keyword);
        } else {
            $orders = $this->model->getAllOrders();
        }
        $this->respond(200, "Thành công", $orders);
    }

    // URL: http://localhost/shop_ban_hang/api/adminOrder/detail?id=1

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $order = $this->model->getOrderById($id);
            if ($order) {
                $order['items'] = $this->model->getOrderDetails($id);
                $this->respond(200, "Thành công", $order);
            }
            $this->respond(404, "Không tìm thấy đơn hàng");
        }
        $this->respond(400, "Thiếu id");
    }

    // URL: http://localhost/shop_ban_hang/api/adminOrder/update_status?id=1

    public function update_status()
    {
        $data = $this->getJsonPayload();
        if (isset($data['order_id'], $data['status'])) {
            $success = $this->model->updateStatus($data['order_id'], $data['status']);
            if ($success) $this->respond(200, "Cập nhật trạng thái thành công");
            $this->respond(500, "Cập nhật status thất bại");
        }
        $this->respond(400, "Thiếu order_id hoặc status");
    }
}
