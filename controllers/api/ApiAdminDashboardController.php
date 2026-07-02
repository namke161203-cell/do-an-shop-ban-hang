<?php
require_once 'models/AdminDashboardModel.php';

class ApiAdminDashboardController extends BaseApiController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminDashboardModel();
    }

    // URL: http://localhost/shop_ban_hang/api/adminDashboard/overview

    public function overview()
    {
        $data = [
            "total_revenue" => $this->model->getTotalRevenue(),
            "pending_orders" => $this->model->getPendingOrdersCount(),
            "total_products" => $this->model->getTotalProductsCount(),
            "revenue_chart" => $this->model->getRevenueChartData(),
            "top_selling" => $this->model->getTopSellingProducts()
        ];
        $this->respond(200, "Thành công", $data);
    }
}
