<?php require_once 'views/admin/layout/header.php'; ?>

<div class="container-fluid">
    <h2 class="mb-4 fw-bold">Dashboard</h2>

    <div class="row mb-4">
        
        <div class="col-md-4">
            <a href="index.php?controller=adminOrder&action=index" class="text-decoration-none">
                <div class="card text-white bg-primary mb-3 shadow h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Tổng Doanh Thu</h5>
                                <h3 class="fw-bold"><?= number_format($revenue, 0, ',', '.') ?> đ</h3>
                                <small>Đơn hàng thành công</small>
                            </div>
                            <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="index.php?controller=adminOrder&action=index" class="text-decoration-none">
                <div class="card text-white bg-warning mb-3 shadow h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Đơn Mới</h5>
                                <h3 class="fw-bold"><?= $newOrders ?></h3>
                                <small>Cần xử lý ngay</small>
                            </div>
                            <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="index.php?controller=adminProduct&action=index" class="text-decoration-none">
                <div class="card text-white bg-success mb-3 shadow h-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Sản Phẩm</h5>
                                <h3 class="fw-bold"><?= $totalProducts ?></h3>
                                <small>Đang kinh doanh</small>
                            </div>
                            <i class="fas fa-box-open fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-bar me-2"></i>Biểu đồ doanh thu 6 tháng</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-crown me-2"></i>Top bán chạy</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Sản phẩm</th>
                                <th class="text-center">Đã bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($topProducts)): ?>
                                <?php foreach($topProducts as $top): ?>
                                    <tr>
                                        <td class="align-middle ps-3">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= $top['image'] ?>" width="40" height="40" class="rounded me-2" style="object-fit: cover;">
                                                <span class="small fw-bold text-truncate" style="max-width: 150px;"><?= $top['name'] ?></span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-danger rounded-pill"><?= $top['total_sold'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="text-center text-muted py-3">Chưa có dữ liệu</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Vẽ biểu đồ
    const labels = <?php echo json_encode(array_column($chartData, 'month')); ?>;
    const data = <?php echo json_encode(array_column($chartData, 'total')); ?>;

    const ctx = document.getElementById('revenueChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: data,
                backgroundColor: '#36a2eb',
                borderColor: '#36a2eb',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

</div>
</body>
</html>