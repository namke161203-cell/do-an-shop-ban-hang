<?php require_once 'views/layouts/header.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center fw-bold">QUÊN MẬT KHẨU</div>
                <div class="card-body">
                    <form action="index.php?controller=user&action=sendResetLink" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nhập email đã đăng ký</label>
                            <input type="email" name="email" class="form-control" required placeholder="example@gmail.com">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Gửi link khôi phục</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <a href="index.php?controller=user&action=login">Quay lại đăng nhập</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'views/layouts/footer.php'; ?>