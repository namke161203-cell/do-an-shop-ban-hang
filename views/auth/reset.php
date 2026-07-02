<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-5" style="min-height: 60vh;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white text-center py-3">
                    <h5 class="fw-bold mb-0 text-uppercase">Đặt Lại Mật Khẩu</h5>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?controller=user&action=updateNewPassword" method="POST">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
                        
                        <div class="mb-3">
                            <label class="fw-bold form-label">Mật khẩu mới</label>
                            <input type="password" name="password" class="form-control" required minlength="6" placeholder="Nhập ít nhất 6 ký tự">
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="confirm_password" class="form-control" required placeholder="Nhập lại mật khẩu trên">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success fw-bold">
                                <i class="fas fa-save me-2"></i> Lưu Mật Khẩu Mới
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>