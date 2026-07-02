<?php
require_once 'models/ProductModel.php';
require_once 'models/OrderModel.php';
// [MỚI] Thêm CartModel để xử lý đồng bộ DB
require_once 'models/CartModel.php';

class CartController {

    public function index() {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        require_once 'views/cart/index.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $size = $_POST['size'];
            $quantity = (int)$_POST['quantity'];
            $actionType = isset($_POST['action_type']) ? $_POST['action_type'] : 'add';

            $productModel = new ProductModel();
            $product = $productModel->getProductDetail($productId);

            if ($product) {

                $key = $productId . '_' . $size;
                $itemData = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'image' => $product['image'],
                    'price' => $product['price'],
                    'size' => $size,
                    'quantity' => $quantity
                ];

                
                if ($actionType == 'buy_now') {
                    $_SESSION['direct_buy'] = [
                        $key => $itemData
                    ];
                    header("Location: index.php?controller=cart&action=checkout&type=direct");
                    exit;

                } else {

                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = [];
                    }
                    if (isset($_SESSION['cart'][$key])) {
                        $_SESSION['cart'][$key]['quantity'] += $quantity;
                    } else {
                        $_SESSION['cart'][$key] = $itemData;
                    }

                    $_SESSION['success'] = "Đã thêm sản phẩm vào giỏ hàng thành công!";

                    if (isset($_SESSION['user'])) {
                        $cartModel = new CartModel();
                        $cartModel->syncCart($_SESSION['user']['id'], $_SESSION['cart']);
                    }


                    if (isset($_SERVER['HTTP_REFERER'])) {
                        header("Location: " . $_SERVER['HTTP_REFERER']);
                    } else {
                        header("Location: index.php?controller=product&action=detail&id=" . $productId);
                    }
                    exit;
                }
            }
        }
        header("Location: index.php");
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $key = $_GET['id'];
            unset($_SESSION['cart'][$key]);

            if (isset($_SESSION['user'])) {
                $cartModel = new CartModel();
                $cartModel->syncCart($_SESSION['user']['id'], $_SESSION['cart']);
            }

        }
        header("Location: index.php?controller=cart&action=index");
    }

    public function update() {
        if (isset($_POST['key']) && isset($_POST['quantity'])) {
            $key = $_POST['key'];
            $qty = (int)$_POST['quantity'];
            
            if ($qty > 0 && isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['quantity'] = $qty;

                if (isset($_SESSION['user'])) {
                    $cartModel = new CartModel();
                    $cartModel->syncCart($_SESSION['user']['id'], $_SESSION['cart']);
                }
            }
        }
        header("Location: index.php?controller=cart&action=index");
    }

    public function checkout() {

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=user&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_items'])) {
            $selectedKeys = $_POST['selected_items'];
            $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            $checkoutItems = [];

            foreach ($selectedKeys as $key) {
                if (isset($cart[$key])) {
                    $checkoutItems[$key] = $cart[$key];
                }
            }

            if (!empty($checkoutItems)) {
                $_SESSION['direct_buy'] = $checkoutItems;
                header("Location: index.php?controller=cart&action=checkout&type=direct");
                exit;
            } else {
                header("Location: index.php?controller=cart&action=index");
                exit;
            }
        }
        $orderType = isset($_GET['type']) ? $_GET['type'] : 'cart'; 
        
        if ($orderType == 'direct') {
            $cart = isset($_SESSION['direct_buy']) ? $_SESSION['direct_buy'] : [];
        } else {
            $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        }

        if (empty($cart)) {
            header("Location: index.php?controller=cart&action=index");
            exit;
        }

        $totalPrice = 0;
        foreach ($cart as $item) $totalPrice += $item['price'] * $item['quantity'];
        
        $user = $_SESSION['user']; 

        require_once 'views/cart/checkout.php';
    }    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
            
            $orderType = isset($_POST['order_type']) ? $_POST['order_type'] : 'cart';
            
            if ($orderType == 'direct') {
                $cartToOrder = isset($_SESSION['direct_buy']) ? $_SESSION['direct_buy'] : [];
            } else {
                $cartToOrder = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            }

            if (empty($cartToOrder)) {
                header("Location: index.php");
                exit;
            }

            $customerData = [
                'fullname' => $_POST['fullname'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'note' => $_POST['note'],
                'total_money' => $_POST['total_money'],
                'payment_method' => $_POST['payment_method'] 
            ];
            
            $orderModel = new OrderModel();
            $orderId = $orderModel->createOrder($_SESSION['user']['id'], $customerData, $cartToOrder);

            if ($orderId) {
                $_SESSION['latest_order'] = [
                    'id' => $orderId,
                    'total_money' => $_POST['total_money'],
                    'payment_method' => $_POST['payment_method'] 
                ];

                if ($orderType == 'direct') {
                    unset($_SESSION['direct_buy']);

                } else {
                    unset($_SESSION['cart']);

                    $cartModel = new CartModel();
                    $cartModel->syncCart($_SESSION['user']['id'], []);
                }
                
                header("Location: index.php?controller=cart&action=success");
                exit; 
            } else {
                echo "Đặt hàng thất bại.";
            }
        }
    }
    
    public function success() {
        if (isset($_SESSION['latest_order'])) {
            $order = $_SESSION['latest_order'];
            require_once 'views/cart/success.php';
        } else {
            header("Location: index.php");
        }
    }
}
?>