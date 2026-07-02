<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold">Sửa thông tin đơn hàng #<?= $order['id'] ?></h5>
                </div>
                <div class="card-body">
                    <form action="index.php?controller=user&action=updateOrder" method="POST">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Người nhận</label>
                            <input type="text" class="form-control bg-light" value="<?= $order['fullname'] ?>" readonly>
                            <small class="text-muted">Không được đổi tên người nhận</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="<?= $order['phone'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="3" required><?= $order['address'] ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tổng tiền</label>
                            <input type="text" class="form-control bg-light" value="<?= number_format($order['total_money']) ?> đ" readonly>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php?controller=user&action=orders" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" class="btn btn-primary fw-bold">Cập nhật thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>