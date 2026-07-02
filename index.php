<?php
// 1. Hiển thị lỗi để dễ debug (Tắt khi chạy thật)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'config/constants.php';
require_once 'config/db.php';

// 4. Lấy Controller và Action từ URL
$controllerInput = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// 5. Chuẩn hóa tên Controller admin -> AdminController
$controllerName = ucfirst($controllerInput) . 'Controller';

// 6. Đường dẫn file Controller (Phân tách logic API)
if (strpos(strtolower($controllerInput), 'api') === 0) {
    // API request -> load from controllers/api/
    $controllerPath = "controllers/api/$controllerName.php";

    // Tự động nạp file BaseApiController
    require_once "controllers/api/BaseApiController.php";
} else {
    // Normal web request -> load from controllers/
    $controllerPath = "controllers/$controllerName.php";
}

// 7. KIỂM TRA & GỌI FILE
if (!file_exists($controllerPath)) {
    die("LỖI 404: Không tìm thấy file Controller tại: <strong>$controllerPath</strong>. <br>Hãy kiểm tra xem bạn đã tạo file này trong thư mục controllers chưa?");
}

require_once $controllerPath;

// 8. KIỂM TRA CLASS
if (!class_exists($controllerName)) {
    die("LỖI 404: File tồn tại nhưng không thấy Class tên là <strong>$controllerName</strong> bên trong. <br>Hãy kiểm tra lại tên class trong file PHP.");
}

$controller = new $controllerName();

// 9. KIỂM TRA HÀM (ACTION)
if (!method_exists($controller, $actionName)) {
    die("LỖI 404: Không tìm thấy hàm (action) tên là <strong>$actionName</strong> trong $controllerName.");
}

// 10. Chạy hàm
$controller->$actionName();
