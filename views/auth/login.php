<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 fw-bold">ĐĂNG NHẬP</h3>
                    
                    <?php if(isset($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger text-center"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?controller=user&action=login">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">Đăng nhập</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="index.php?controller=user&action=forgotPassword" class="small text-muted">Quên mật khẩu?</a>
                    </div>
                    
                    <hr>
                    <div class="text-center">
                        <a href="index.php?controller=user&action=register" class="text-decoration-none">Tạo tài khoản mới</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>