<?php require_once 'views/admin/layout/header.php'; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header fw-bold">Chi tiết sản phẩm</div>
            <div class="card-body">
                <table class="table">
                    <thead><tr><th>Sản phẩm</th><th>Size</th><th>Giá</th><th>SL</th><th>Tổng</th></tr></thead>
                    <tbody>
                        <?php foreach($details as $item): ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td><span class="badge bg-secondary"><?= $item['size'] ?></span></td>
                            <td><?= number_format($item['price']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['price'] * $item['quantity']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">Cập nhật trạng thái</div>
            <div class="card-body">
                
                <div class="d-grid gap-2 mb-3 border-bottom pb-3">
                    <a href="index.php?controller=adminOrder&action=invoice&id=<?= $order['id'] ?>" 
                       target="_blank" 
                       class="btn btn-secondary">
                        <i class="fas fa-print me-2"></i> In hóa đơn
                    </a>
                </div>
                <p><strong>Khách hàng:</strong> <?= $order['fullname'] ?></p>
                
                <p><strong>Số điện thoại:</strong> 
                    <?php 
                        if (!empty($order['phone'])) {
                            echo $order['phone'];
                        } elseif (!empty($order['customer_phone'])) {
                            echo $order['customer_phone'];
                        } else {
                            echo '<span class="text-danger">Không có</span>';
                        }
                    ?>
                </p>
                <p><strong>Địa chỉ:</strong> <?= $order['address'] ?></p>
                
                <form action="index.php?controller=adminOrder&action=update_status" method="POST">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <div class="mb-3">
                        <label>Trạng thái hiện tại:</label>
                        <select name="status" class="form-select">
                            <option value="pending" <?= $order['status']=='pending'?'selected':'' ?>>Chờ xử lý (Pending)</option>
                            <option value="confirmed" <?= $order['status']=='confirmed'?'selected':'' ?>>Đã xác nhận (Confirmed)</option>
                            <option value="shipping" <?= $order['status']=='shipping'?'selected':'' ?>>Đang giao (Shipping)</option>
                            <option value="completed" <?= $order['status']=='completed'?'selected':'' ?>>Hoàn thành (Completed)</option>
                            <option value="cancelled" <?= $order['status']=='cancelled'?'selected':'' ?>>Hủy đơn (Cancelled)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Cập nhật</button>
                    <small class="text-muted d-block mt-2">* Lưu ý: Chuyển sang "Đang giao" sẽ trừ kho. Chuyển sang "Hủy" sẽ cộng lại kho.</small>
                </form>
            </div>
        </div>
    </div>
</div>
</div></body></html>