<?php
require_once 'models/CartModel.php';

class ApiCartController extends BaseApiController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new CartModel();
    }

    // URL: http://localhost/shop_ban_hang/api/cart/get

    public function get()
    {
        $userId = $_GET['user_id'] ?? null;
        if ($userId) {
            $cart = $this->model->getCartByUserId($userId);
            $this->respond(200, "Thành công", $cart);
        }
        $this->respond(400, "Thiếu user_id");
    }

    // URL: http://localhost/shop_ban_hang/api/cart/sync

    public function sync()
    {
        $data = $this->getJsonPayload();
        $userId = $data['user_id'] ?? null;
        $cartItems = $data['cart_items'] ?? [];

        if ($userId) {
            $this->model->syncCart($userId, $cartItems);
            $this->respond(200, "Đồng bộ giỏ hàng thành công");
        }
        $this->respond(400, "Thiếu user_id để đồng bộ");
    }
}
