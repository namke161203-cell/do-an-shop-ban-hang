<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="index.php?controller=user&action=profile" class="list-group-item list-group-item-action active">
                    <i class="fas fa-user-circle me-2"></i> Thông tin tài khoản
                </a>
                <a href="index.php?controller=user&action=orders" class="list-group-item list-group-item-action">
                    <i class="fas fa-history me-2"></i> Lịch sử mua hàng
                </a>
                <a href="index.php?controller=user&action=logout" class="list-group-item list-group-item-action text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-white fw-bold">HỒ SƠ CÁ NHÂN</div>
                <div class="card-body">
                    <?php if(!empty($message)): ?>
                        <div class="alert alert-success"><?= $message ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label>Họ và tên</label>
                            <input type="text" name="fullname" class="form-control" value="<?= $user['fullname'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Email (Không thể thay đổi)</label>
                            <input type="email" class="form-control" value="<?= $user['email'] ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="<?= $user['phone'] ?>">
                        </div>
                        <div class="mb-3">
                            <label>Địa chỉ giao hàng</label>
                            <textarea name="address" class="form-control" rows="3"><?= $user['address'] ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>