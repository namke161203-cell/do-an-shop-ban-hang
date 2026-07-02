<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-5">
    <h2 class="text-center mb-4 text-uppercase fw-bold">Giỏ hàng của bạn</h2>

    <?php if (empty($cart)): ?>
        <div class="text-center">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" alt="Empty Cart" style="width: 200px;">
            <p class="mt-3">Giỏ hàng trống trơn!</p>
            <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        
        <form action="index.php?controller=cart&action=checkout" method="POST" id="cartForm">
            
            <div class="row">
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">
                                        <input type="checkbox" id="checkAll" class="form-check-input" onclick="toggleAll(this)">
                                    </th>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th>Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $key => $item): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_items[]" 
                                                   value="<?= $key ?>" 
                                                   class="form-check-input item-check"
                                                   data-price="<?= $item['price'] * $item['quantity'] ?>"
                                                   onclick="updateTotal()">
                                        </td>
                                        
                                        <td class="text-start">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($item['image']) ?>" class="rounded me-2" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-bold"><?= htmlspecialchars($item['name']) ?></div>
                                                    <small class="text-muted">Size: <?= $item['size'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $item['quantity'] ?></span>
                                        </td>
                                        <td class="fw-bold text-danger">
                                            <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ
                                        </td>
                                        <td>
                                            <a href="index.php?controller=cart&action=delete&id=<?= $key ?>" class="text-danger" onclick="return confirm('Xóa sản phẩm này?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white fw-bold">Tổng thanh toán</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span>Đã chọn:</span>
                                <span class="fw-bold" id="selectedCount">0 sản phẩm</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <span>Tạm tính:</span>
                                <span class="fw-bold text-danger fs-5" id="totalMoney">0đ</span>
                            </div>
                            
                            <button type="submit" id="btnCheckout" class="btn btn-danger w-100 fw-bold" disabled>
                                MUA HÀNG
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    <?php endif; ?>
</div>

<script>
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace('₫', 'đ');
    }

    // Hàm cập nhật tổng tiền khi tick checkbox
    function updateTotal() {
        let checkboxes = document.querySelectorAll('.item-check');
        let total = 0;
        let count = 0;
        let allChecked = true;

        checkboxes.forEach(function(cb) {
            if (cb.checked) {
                total += parseInt(cb.getAttribute('data-price'));
                count++;
            } else {
                allChecked = false;
            }
        });

        // Cập nhật giao diện
        document.getElementById('totalMoney').innerText = formatCurrency(total);
        document.getElementById('selectedCount').innerText = count + ' sản phẩm';
        
        // Cập nhật trạng thái nút Mua hàng
        document.getElementById('btnCheckout').disabled = (count === 0);

        // Cập nhật trạng thái checkbox "Chọn tất cả"
        document.getElementById('checkAll').checked = (checkboxes.length > 0 && allChecked);
    }

    // Hàm xử lý nút "Chọn tất cả"
    function toggleAll(source) {
        let checkboxes = document.querySelectorAll('.item-check');
        checkboxes.forEach(function(cb) {
            cb.checked = source.checked;
        });
        updateTotal();
    }
</script>

<?php require_once 'views/layouts/footer.php'; ?>