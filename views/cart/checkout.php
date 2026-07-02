<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4 text-center text-uppercase fw-bold">Thanh Toán Đơn Hàng</h2>
    
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">1. Thông tin người nhận</div>
                <div class="card-body">
                    <form action="index.php?controller=cart&action=processCheckout" method="POST">
                        
                        <input type="hidden" name="order_type" value="<?= isset($orderType) ? $orderType : 'cart' ?>">
                        
                        <input type="hidden" name="total_money" value="<?= $totalPrice ?>">
                        
                        <div class="mb-3">
                            <label class="fw-bold mb-1">Họ và tên người nhận</label>
                            <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold mb-1">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold mb-1">Địa chỉ giao hàng</label>
                            <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea> 
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold mb-1">Ghi chú (Tùy chọn)</label>
                            <textarea name="note" class="form-control" placeholder="Ví dụ: Giao hàng giờ hành chính..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-2">Phương thức thanh toán</label>
                            <div class="form-check p-3 border rounded mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                                <label class="form-check-label fw-bold" for="cod">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i> Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>
                            <div class="form-check p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" id="banking" value="BANKING">
                                <label class="form-check-label fw-bold" for="banking">
                                    <i class="fas fa-university text-primary me-2"></i> Chuyển khoản ngân hàng (QR Code)
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold py-3">
                            <i class="fas fa-check-circle me-2"></i> XÁC NHẬN ĐẶT HÀNG
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm bg-light border-0">
                <div class="card-header bg-white fw-bold">2. Đơn hàng của bạn</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3 rounded">
                        <?php if(!empty($cart)): ?>
                            <?php foreach($cart as $item): ?>
                                <li class="list-group-item d-flex justify-content-between lh-sm bg-transparent">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" class="rounded me-2 border" style="width: 50px; height: 50px; object-fit: cover;">
                                        
                                        <div>
                                            <h6 class="my-0 small fw-bold"><?= htmlspecialchars($item['name']) ?></h6>
                                            <small class="text-muted">Size: <?= $item['size'] ?> | SL: <?= $item['quantity'] ?></small>
                                        </div>
                                    </div>
                                    <span class="text-muted fw-bold"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <li class="list-group-item d-flex justify-content-between bg-white mt-3 border-top fw-bold text-dark fs-5">
                            <span>Tổng cộng</span>
                            <span class="text-danger"><?= number_format($totalPrice, 0, ',', '.') ?>đ</span>
                        </li>
                    </ul>
                    
                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-shipping-fast me-1"></i> Phí vận chuyển sẽ được tính khi nhân viên gọi xác nhận đơn hàng.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>