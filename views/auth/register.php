<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">ĐĂNG KÝ THÀNH VIÊN</h4>
                </div>
                <div class="card-body p-4">
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?controller=user&action=register">
                        <div class="mb-3">
                            <label>Họ và tên (*)</label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email (*)</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Số điện thoại</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Mật khẩu (*)</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Nhập lại mật khẩu (*)</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Địa chỉ giao hàng mặc định</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Đăng Ký Ngay</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    Đã có tài khoản? <a href="index.php?controller=user&action=login">Đăng nhập ngay</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>