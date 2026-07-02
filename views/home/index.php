<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid p-0 mb-5">
    <div style="background: url('https://img.freepik.com/free-photo/soccer-players-action-professional-stadium_654080-1748.jpg') center/cover no-repeat; height: 500px; position: relative;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>
        <div class="container h-100 d-flex align-items-center position-relative text-white">
            <div class="col-md-6">
                <span class="badge bg-danger mb-2">HOT DEAL 2025</span>
                <h1 class="display-3 fw-bold">BỨT TỐC ĐAM MÊ</h1>
                <p class="lead mb-4">Sở hữu những đôi giày đá bóng chính hãng với công nghệ tiên tiến nhất. Giúp bạn kiểm soát bóng tốt hơn, chạy nhanh hơn.</p>
                <a href="index.php?controller=product&action=list" class="btn btn-warning btn-lg fw-bold px-5 rounded-pill">MUA NGAY <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    
    <div class="row text-center mb-5">
        <div class="col-md-3">
            <div class="p-3 border rounded shadow-sm">
                <i class="fas fa-truck fa-2x text-primary mb-3"></i>
                <h5 class="fw-bold">Giao hàng toàn quốc</h5>
                <p class="text-muted small mb-0">Nhận hàng trong 2-3 ngày</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 border rounded shadow-sm">
                <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                <h5 class="fw-bold">Chính hãng 100%</h5>
                <p class="text-muted small mb-0">Cam kết đền gấp 10 nếu fake</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 border rounded shadow-sm">
                <i class="fas fa-exchange-alt fa-2x text-warning mb-3"></i>
                <h5 class="fw-bold">Đổi trả 7 ngày</h5>
                <p class="text-muted small mb-0">Nếu không vừa size</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 border rounded shadow-sm">
                <i class="fas fa-headset fa-2x text-danger mb-3"></i>
                <h5 class="fw-bold">Hỗ trợ 24/7</h5>
                <p class="text-muted small mb-0">Tư vấn chọn giày chuẩn</p>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2 class="fw-bold text-uppercase"><i class="fas fa-fire text-danger me-2"></i>Sản phẩm mới về</h2>
        <a href="index.php?controller=product&action=list" class="btn btn-outline-dark rounded-pill">Xem tất cả <i class="fas fa-angle-right"></i></a>
    </div>

    <div class="row">
        <?php if(!empty($newProducts)): ?>
            <?php foreach($newProducts as $p): ?>
                <?php 
                    // --- [LOGIC MỚI] XÁC ĐỊNH GIÁ CHUẨN (ANCHOR PRICE) ---
                    // Nếu có giá gốc (2.1tr) -> Lấy giá gốc làm chuẩn.
                    // Nếu không có giá gốc -> Lấy giá bán thường (1.85tr) làm chuẩn.
                    $anchor_price = ($p['old_price'] > 0) ? $p['old_price'] : $p['price'];

                    // --- LOGIC KIỂM TRA FLASH SALE ---
                    $is_flash_sale = false;
                    $sale_percent = 0;
                    
                    if (!empty($p['sale_price']) && $p['sale_price'] > 0 && !empty($p['sale_start']) && !empty($p['sale_end'])) {
                        $now = time();
                        $start = strtotime($p['sale_start']);
                        $end = strtotime($p['sale_end']);
                        
                        if ($now >= $start && $now <= $end) {
                            $is_flash_sale = true;
                            // Tính % giảm giá: Từ Giá Chuẩn -> Giá Sale
                            if($anchor_price > 0) {
                                $sale_percent = round((($anchor_price - $p['sale_price']) / $anchor_price) * 100);
                            }
                        }
                    }

                    // Nếu không phải Flash Sale thì tính % thường: Từ Giá Chuẩn -> Giá Bán
                    if (!$is_flash_sale && $p['old_price'] > $p['price']) {
                        $sale_percent = round((($p['old_price'] - $p['price']) / $p['old_price']) * 100);
                    }
                ?>

                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card card-product h-100 border-0 shadow-sm position-relative">
                        
                        <?php if($is_flash_sale): ?>
                            <div class="position-absolute top-0 start-0 bg-warning text-dark px-2 py-1 m-2 rounded small fw-bold shadow-sm" style="z-index: 10;">
                                <i class="fas fa-bolt"></i> -<?= $sale_percent ?>%
                            </div>
                            <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 rounded-bottom small fw-bold" style="font-size: 0.7rem;">
                                <i class="far fa-clock"></i> Kết thúc: <?= date('H:i', strtotime($p['sale_end'])) ?>
                            </div>
                        <?php elseif($sale_percent > 0): ?>
                            <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 m-2 rounded small fw-bold">
                                -<?= $sale_percent ?>%
                            </div>
                        <?php endif; ?>

                        <a href="index.php?controller=product&action=detail&id=<?= $p['id'] ?>">
                            <img src="<?= $p['image'] ?>" class="card-img-top p-3" alt="<?= $p['name'] ?>" style="height: 220px; object-fit: contain;">
                        </a>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="small text-muted mb-1"><?= $p['cat_name'] ?? 'Giày bóng đá' ?></div>
                            <h6 class="card-title text-truncate">
                                <a href="index.php?controller=product&action=detail&id=<?= $p['id'] ?>" class="text-decoration-none text-dark">
                                    <?= $p['name'] ?>
                                </a>
                            </h6>
                            
                            <div class="mt-auto">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    
                                    <?php if($is_flash_sale): ?>
                                        <div>
                                            <span class="text-danger fw-bold fs-5"><?= number_format($p['sale_price'], 0, ',', '.') ?>đ</span>
                                            <div class="small text-muted text-decoration-line-through"><?= number_format($anchor_price, 0, ',', '.') ?>đ</div>
                                        </div>
                                        <i class="fas fa-fire text-warning animate__animated animate__pulse animate__infinite"></i>
                                    
                                    <?php else: ?>
                                        <span class="text-dark fw-bold fs-5"><?= number_format($p['price'], 0, ',', '.') ?>đ</span>
                                        <?php if($p['old_price'] > 0): ?>
                                            <small class="text-muted text-decoration-line-through"><?= number_format($p['old_price'], 0, ',', '.') ?>đ</small>
                                        <?php endif; ?>

                                    <?php endif; ?>

                                </div>
                                <a href="index.php?controller=product&action=detail&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary w-100 mt-2">
                                    Chọn mua
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Đang cập nhật sản phẩm...</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="row my-5">
        <div class="col-md-6 mb-3">
            <div class="rounded p-4 text-white d-flex align-items-center" style="background: linear-gradient(45deg, #11998e, #38ef7d); min-height: 150px;">
                <div>
                    <h4>GIÀY SÂN CỎ NHÂN TẠO</h4>
                    <p>Bám sân cực tốt, độ bền cao</p>
                    <a href="index.php?controller=product&action=list&cat=1" class="btn btn-light btn-sm fw-bold">Khám phá ngay</a>
                </div>
                <i class="fas fa-running fa-4x ms-auto opacity-50"></i>
            </div>
        </div>
        <div class="col-md-6">
            <div class="rounded p-4 text-white d-flex align-items-center" style="background: linear-gradient(45deg, #ff512f, #dd2476); min-height: 150px;">
                <div>
                    <h4>GIÀY SÂN CỎ TỰ NHIÊN</h4>
                    <p>Bứt tốc mạnh mẽ, kiểm soát bóng</p>
                    <a href="index.php?controller=product&action=list&cat=2" class="btn btn-light btn-sm fw-bold">Khám phá ngay</a>
                </div>
                <i class="fas fa-futbol fa-4x ms-auto opacity-50"></i>
            </div>
        </div>
    </div>

</div>

<?php require_once 'views/layouts/footer.php'; ?>