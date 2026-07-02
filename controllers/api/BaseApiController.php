<?php
class BaseApiController
{
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }


    protected function respond($status, $message, $data = null)
    {
        http_response_code($status);
        $response = ["status" => $status, "message" => $message];
        if ($data !== null) {
            $response["data"] = $data;
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    protected function getJsonPayload()
    {
        $input = file_get_contents("php://input");
        if (!empty($input)) {
            $decoded = json_decode($input, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        // Cho phép hứng cả dữ liệu từ URL (?id=...&) để test trên trình duyệt
        return !empty($_POST) ? $_POST : $_GET;
    }
}
