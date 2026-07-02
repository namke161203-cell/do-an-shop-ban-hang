<?php require_once 'views/layouts/header.php'; ?>

<div class="bg-light py-4 mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Danh sách sản phẩm</li>
            </ol>
        </nav>
        <h2 class="fw-bold mt-2">
            <?php 
                if (!empty($keyword)) echo 'Tìm kiếm: "' . htmlspecialchars($keyword) . '"';
                elseif ($categoryId == 1) echo 'Giày Cỏ Nhân Tạo';
                elseif ($categoryId == 2) echo 'Giày Cỏ Tự Nhiên';
                elseif ($categoryId == 3) echo 'Giày Futsal';
                elseif ($categoryId == 4) echo 'Phụ Kiện';
                else echo 'Tất cả sản phẩm';
            ?>
        </h2>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold text-uppercase">
                    <i class="fas fa-filter me-2 text-warning"></i> Bộ lọc
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">Danh mục</h6>
                    <ul class="list-unstyled">
                        <li><a href="index.php?controller=product&action=list" class="text-decoration-none text-dark d-block py-1">Tất cả</a></li>
                        <li><a href="index.php?controller=product&action=list&category=1" class="text-decoration-none text-dark d-block py-1">Cỏ nhân tạo</a></li>
                        <li><a href="index.php?controller=product&action=list&category=2" class="text-decoration-none text-dark d-block py-1">Cỏ tự nhiên</a></li>
                        <li><a href="index.php?controller=product&action=list&category=3" class="text-decoration-none text-dark d-block py-1">Giày Futsal</a></li>
                        <li><a href="index.php?controller=product&action=list&category=4" class="text-decoration-none text-dark d-block py-1">Phụ kiện</a></li>
                    </ul>
                    <hr>
                    <h6 class="fw-bold">Sắp xếp</h6>
                    <select class="form-select form-select-sm" onchange="location = this.value;">
                        <option value="index.php?controller=product&action=list&sort=newest" <?= ($sort == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                        <option value="index.php?controller=product&action=list&sort=price_asc" <?= ($sort == 'price_asc') ? 'selected' : '' ?>>Giá tăng dần</option>
                        <option value="index.php?controller=product&action=list&sort=price_desc" <?= ($sort == 'price_desc') ? 'selected' : '' ?>>Giá giảm dần</option>
                        <option value="index.php?controller=product&action=list&sort=name_az" <?= ($sort == 'name_az') ? 'selected' : '' ?>>Tên A-Z</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <?php if(empty($products)): ?>
                <div class="alert alert-warning text-center">
                    <i class="fas fa-search me-2"></i> Không tìm thấy sản phẩm nào phù hợp.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach($products as $product): ?>
                        <?php 
                           
                            $anchor_price = ($product['old_price'] > 0) ? $product['old_price'] : $product['price'];

                            // B. Kiểm tra thời gian Flash Sale
                            $is_flash_sale = false;
                            if (!empty($product['sale_price']) && $product['sale_price'] > 0 && !empty($product['sale_start']) && !empty($product['sale_end'])) {
                                // Set múi giờ VN để chắc chắn
                                date_default_timezone_set('Asia/Ho_Chi_Minh');
                                $now = time();
                                $start = strtotime($product['sale_start']);
                                $end = strtotime($product['sale_end']);
                                
                                if ($now >= $start && $now <= $end) {
                                    $is_flash_sale = true;
                                }
                            }

                            // C. Tính Giá hiển thị & % Giảm
                            $display_price = $product['price']; // Mặc định là giá thường
                            $percent = 0;

                            if ($is_flash_sale) {
                                // --- TRƯỜNG HỢP FLASH SALE ---
                                $display_price = $product['sale_price'];
                                if($anchor_price > 0) {
                                    $percent = round((($anchor_price - $display_price) / $anchor_price) * 100);
                                }
                            } else {
                                // --- TRƯỜNG HỢP BÁN THƯỜNG ---
                                if ($product['old_price'] > $product['price']) {
                                    $percent = round((($product['old_price'] - $product['price']) / $product['old_price']) * 100);
                                }
                            }
                        ?>

                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 card-hover position-relative">
                            
                            <?php if ($percent > 0): ?>
                                <div class="position-absolute top-0 start-0 m-2 badge <?= $is_flash_sale ? 'bg-warning text-dark' : 'bg-danger text-white' ?> shadow-sm z-1">
                                    <?= $is_flash_sale ? '<i class="fas fa-bolt"></i>' : '' ?> -<?= $percent ?>%
                                </div>
                            <?php endif; ?>

                            <a href="index.php?controller=product&action=detail&id=<?= $product['id'] ?>" class="text-decoration-none text-dark">
                                <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top p-3 object-fit-contain" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 220px;">
                                
                                <div class="card-body text-center d-flex flex-column">
                                    <h6 class="card-title fw-bold text-truncate" title="<?= htmlspecialchars($product['name']) ?>">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </h6>
                                    
                                    <div class="mt-auto">
                                        <div class="text-danger fw-bold fs-5">
                                            <?= number_format($display_price, 0, ',', '.') ?> đ
                                        </div>

                                        <?php if ($percent > 0): ?>
                                            <div class="text-muted text-decoration-line-through small">
                                                <?= number_format($anchor_price, 0, ',', '.') ?> đ
                                            </div>
                                        <?php else: ?>
                                            <div class="small text-white">.</div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="d-grid mt-3">
                                        <button class="btn btn-outline-dark btn-sm rounded-pill">
                                            Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="index.php?controller=product&action=list&page=<?= $i ?>&keyword=<?= $keyword ?>&category=<?= $categoryId ?>&sort=<?= $sort ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
                
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
    .card-hover:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>

<?php require_once 'views/layouts/footer.php'; ?>