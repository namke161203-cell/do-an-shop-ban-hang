<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-5">
    <h3 class="mb-4 fw-bold text-uppercase"><i class="fas fa-history me-2"></i>Lịch sử đơn hàng</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (!empty($orders)): ?>
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>HT Thanh toán</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><strong>#<?= $order['id'] ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td class="text-danger fw-bold"><?= number_format($order['total_money'], 0, ',', '.') ?> đ</td>
                                
                                <td>
                                    <?php $method = $order['payment_method'] ?? 'COD'; ?>
                                    <?php if ($method == 'BANKING'): ?>
                                         <span class="badge bg-primary">Chuyển khoản Ngân hàng</span> 
                                    <?php else: ?>
                                         <span class="badge bg-secondary">Tiền mặt (COD)</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <?php 
                                        if ($order['status'] == 'pending') echo '<span class="badge bg-warning text-dark">Chờ xử lý</span>';
                                        elseif ($order['status'] == 'completed') echo '<span class="badge bg-success">Hoàn thành</span>';
                                        elseif ($order['status'] == 'cancelled') echo '<span class="badge bg-danger">Đã hủy</span>';
                                        else echo '<span class="badge bg-secondary">Processing</span>';
                                    ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($order['status'] == 'pending'): ?>
                                        <a href="index.php?controller=user&action=editOrder&id=<?= $order['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary me-1" 
                                           title="Sửa địa chỉ/SĐT">
                                           <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <a href="index.php?controller=user&action=cancelOrder&id=<?= $order['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Bạn có chắc chắn muốn HỦY đơn hàng này không?');"
                                           title="Hủy đơn hàng">
                                           <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php else: ?>
                                        <small class="text-muted fst-italic">Không thể thao tác</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Bạn chưa có đơn hàng nào.</p>
                    <a href="index.php" class="btn btn-primary">Mua sắm ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>