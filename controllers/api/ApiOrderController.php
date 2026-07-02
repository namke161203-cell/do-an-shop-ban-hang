<?php
require_once 'models/OrderModel.php';

class ApiOrderController extends BaseApiController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new OrderModel();
    }

    // URL: http://localhost/shop_ban_hang/api/order/create

    public function create()
    {
        $data = $this->getJsonPayload();
        if (isset($data['user_id'], $data['customer_data'], $data['cart_items'])) {
            $orderId = $this->model->createOrder($data['user_id'], $data['customer_data'], $data['cart_items']);
            if ($orderId) {
                $this->respond(201, "Đặt hàng thành công", ["order_id" => $orderId]);
            }
            $this->respond(500, "Lỗi khi tạo đơn hàng");
        }
        $this->respond(400, "Thiếu dữ liệu tạo đơn hàng");
    }

    // URL: http://localhost/shop_ban_hang/api/order/history

    public function history()
    {
        $userId = $_GET['user_id'] ?? null;
        if ($userId) {
            $orders = $this->model->getOrdersByUserId($userId);
            $this->respond(200, "Thành công", $orders);
        }
        $this->respond(400, "Thiếu user_id");
    }

    // URL: http://localhost/shop_ban_hang/api/order/detail

    public function detail()
    {
        $orderId = $_GET['id'] ?? null;
        if ($orderId) {
            $order = $this->model->getOrderById($orderId);
            $details = $this->model->getOrderDetail($orderId);
            if ($order) {
                $order['items'] = $details;
                $this->respond(200, "Thành công", $order);
            }
            $this->respond(404, "Không tìm thấy đơn hàng");
        }
        $this->respond(400, "Thiếu order id");
    }

    // URL: http://localhost/shop_ban_hang/api/order/cancel

    public function cancel()
    {
        $data = $this->getJsonPayload();
        if (isset($data['order_id'], $data['user_id'])) {
            $success = $this->model->cancelOrder($data['order_id'], $data['user_id']);
            if ($success) $this->respond(200, "Huỷ đơn hàng thành công");
            else $this->respond(400, "Không thể huỷ đơn hàng này");
        }
        $this->respond(400, "Thiếu order_id hoặc user_id");
    }
}
