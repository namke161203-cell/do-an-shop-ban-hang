<?php require_once 'views/layouts/header.php'; ?>

<?php
    $myBank = [
        'bank_id' => 'VCB',       
        'account_no' => '1035476836', 
        'account_name' => 'DINH HAI NAM', 
        'template' => 'compact'   
    ];

    if ($order['payment_method'] == 'BANKING') {
        $amount = $order['total_money'];
        $content = "THANHTOAN DH " . $order['id']; 
        
        $qrUrl = "https://img.vietqr.io/image/{$myBank['bank_id']}-{$myBank['account_no']}-{$myBank['template']}.png?amount={$amount}&addInfo={$content}&accountName={$myBank['account_name']}";
    }
?>

<div class="container py-5 text-center">
    
    <?php if ($order['payment_method'] == 'BANKING'): ?>
        <h2 class="fw-bold text-primary mb-3">THANH TOÁN ĐƠN HÀNG #<?= $order['id'] ?></h2>
        <div class="alert alert-warning d-inline-block">
            Vui lòng quét mã QR dưới đây để hoàn tất thanh toán.
        </div>
        
        <div class="row justify-content-center mt-3">
            <div class="col-md-5">
                <div class="card shadow border-primary">
                    <div class="card-body p-4">
                        <img src="<?= $qrUrl ?>" class="img-fluid mb-3" style="max-width: 300px; border: 2px solid #ddd; border-radius: 10px;">
                        
                        <hr>
                        <div class="text-start">
                            <p class="mb-1"><strong>Ngân hàng:</strong> Vietcombank</p>
                            <p class="mb-1"><strong>Chủ tài khoản:</strong> <?= $myBank['account_name'] ?></p>
                            <p class="mb-1"><strong>Số tài khoản:</strong> <span class="text-danger fw-bold fs-5"><?= $myBank['account_no'] ?></span></p>
                            <p class="mb-1"><strong>Số tiền:</strong> <span class="text-danger fw-bold fs-5"><?= number_format($order['total_money']) ?> đ</span></p>
                            <p class="mb-0"><strong>Nội dung CK:</strong> <span class="badge bg-warning text-dark fs-6">THANHTOAN DH <?= $order['id'] ?></span></p>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-muted small">
                        Hệ thống sẽ xử lý đơn hàng ngay khi nhận được tiền.
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="index.php" class="btn btn-outline-primary">Quay về trang chủ</a>
            <a href="index.php?controller=user&action=orders" class="btn btn-primary">Kiểm tra đơn hàng</a>
        </div>

    <?php else: ?>
        <div class="card shadow-sm p-5 border-0" style="max-width: 600px; margin: 0 auto;">
            <div class="mb-4">
                <i class="fas fa-check-circle fa-5x text-success"></i>
            </div>
            <h2 class="fw-bold text-success">ĐẶT HÀNG THÀNH CÔNG!</h2>
            <p class="lead mt-3">Cảm ơn bạn đã mua sắm tại Sport Kicks.</p>
            <p class="text-muted">Đơn hàng <strong>#<?= $order['id'] ?></strong> đã được lưu. Nhân viên sẽ gọi điện xác nhận sớm nhất.</p>
            
            <div class="mt-4">
                <a href="index.php" class="btn btn-outline-dark me-2">Tiếp tục mua sắm</a>
                <a href="index.php?controller=user&action=orders" class="btn btn-primary">Xem đơn hàng của tôi</a>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php require_once 'views/layouts/footer.php'; ?>