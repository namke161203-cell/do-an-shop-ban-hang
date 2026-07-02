<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-5">

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row mb-5">
        
        <div class="col-md-6">
            <div class="position-relative mb-3 text-center border rounded shadow-sm p-2 bg-white">
                
                <?php 
                    // =============================================================
                    // 1. LOGIC TÍNH TOÁN GIÁ FLASH SALE (MỚI THÊM VÀO)
                    // =============================================================
                    
                    // A. Xác định Giá Gốc để gạch (Nếu có giá gốc cũ thì lấy, ko thì lấy giá bán thường)
                    $anchor_price = ($product['old_price'] > 0) ? $product['old_price'] : $product['price'];

                    // B. Kiểm tra thời gian Flash Sale
                    $is_flash_sale = false;
                    $percent = 0;
                    
                    // Kiểm tra dữ liệu có tồn tại không
                    if (!empty($product['sale_price']) && $product['sale_price'] > 0 && !empty($product['sale_start']) && !empty($product['sale_end'])) {
                        // Set múi giờ cho chắc chắn
                        date_default_timezone_set('Asia/Ho_Chi_Minh'); 
                        $now = time();
                        $start = strtotime($product['sale_start']);
                        $end = strtotime($product['sale_end']);
                        
                        // Nếu thời gian hiện tại nằm trong khung giờ
                        if ($now >= $start && $now <= $end) {
                            $is_flash_sale = true;
                        }
                    }

                    // C. Tính toán hiển thị
                    if ($is_flash_sale) {
                        // Đang Sale: Giá hiện tại là Giá Sale
                        $current_price = $product['sale_price'];
                        // Tính % giảm từ Giá Gốc -> Giá Sale
                        if($anchor_price > 0) {
                            $percent = round((($anchor_price - $current_price) / $anchor_price) * 100);
                        }
                    } else {
                        // Không Sale: Giá hiện tại là Giá Thường
                        $current_price = $product['price'];
                        // Tính % giảm từ Giá Gốc -> Giá Thường (nếu có)
                        if ($product['old_price'] > $product['price']) {
                            $percent = round((($product['old_price'] - $product['price']) / $product['old_price']) * 100);
                        }
                    }
                ?>

                <?php if ($percent > 0): ?>
                    <div class="position-absolute top-0 end-0 m-3 badge <?= $is_flash_sale ? 'bg-warning text-dark' : 'bg-danger text-white' ?> fs-5 shadow z-1">
                        <?= $is_flash_sale ? '<i class="fas fa-bolt"></i>' : '' ?> Giảm <?= $percent ?>%
                    </div>
                <?php endif; ?>

                <img id="mainImage" src="<?= htmlspecialchars($product['image']) ?>" 
                     class="img-fluid rounded object-fit-contain" 
                     style="height: 450px; width: 100%; transition: opacity 0.3s ease;" 
                     alt="<?= htmlspecialchars($product['name']) ?>">
            </div>

            <div class="d-flex justify-content-center gap-2 overflow-auto py-2">
                <div class="border border-danger border-2 rounded p-1 cursor-pointer thumb-item active" 
                     onclick="changeImage(this, '<?= htmlspecialchars($product['image']) ?>')">
                    <img src="<?= htmlspecialchars($product['image']) ?>" 
                         class="rounded" 
                         style="width: 70px; height: 70px; object-fit: cover;">
                </div>

                <?php if (!empty($secondaryImages)): ?>
                    <?php foreach ($secondaryImages as $img): ?>
                        <div class="border rounded p-1 cursor-pointer thumb-item" 
                             onclick="changeImage(this, '<?= htmlspecialchars($img['image_url']) ?>')">
                            <img src="<?= htmlspecialchars($img['image_url']) ?>" 
                                 class="rounded" 
                                 style="width: 70px; height: 70px; object-fit: cover;">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <h2 class="fw-bold"><?= htmlspecialchars($product['name']) ?></h2>
            <p class="text-muted">Thương hiệu: <span class="fw-bold"><?= htmlspecialchars($product['brand_name']) ?></span></p>
            
            <div class="mb-3">
                <?php if ($is_flash_sale): ?>
                    <div class="alert alert-warning d-inline-flex align-items-center px-3 py-1 mb-2 border-warning text-dark fw-bold">
                        <i class="fas fa-bolt me-2 animate__animated animate__flash animate__infinite"></i>
                        Flash Sale kết thúc: <?= date('H:i d/m', strtotime($product['sale_end'])) ?>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <span class="text-danger fw-bold fs-2 me-3">
                            <?= number_format($current_price, 0, ',', '.') ?> đ
                        </span>
                        
                        <span class="text-muted text-decoration-line-through fs-5">
                            <?= number_format($anchor_price, 0, ',', '.') ?> đ
                        </span>
                    </div>

                <?php else: ?>
                    <div class="d-flex align-items-center">
                        <span class="text-danger fw-bold fs-2 me-3">
                            <?= number_format($current_price, 0, ',', '.') ?> đ
                        </span>
                        
                        <?php if ($product['old_price'] > $product['price']): ?>
                            <span class="text-muted text-decoration-line-through fs-5">
                                <?= number_format($product['old_price'], 0, ',', '.') ?> đ
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <p class="text-secondary"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            
            <form action="index.php?controller=cart&action=add" method="POST">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                
                <div class="mb-3">
                    <label class="fw-bold mb-2">Chọn Size: <span id="stock-message" class="text-danger small fst-italic ms-2"></span></label>
                    <div class="mt-2">
                        <?php if(!empty($product['variants'])): ?>
                            <?php foreach($product['variants'] as $v): ?>
                                <input type="radio" class="btn-check size-radio" 
                                       name="size" 
                                       id="size_<?= $v['size'] ?>" 
                                       value="<?= $v['size'] ?>" 
                                       data-stock="<?= $v['stock'] ?>"
                                       required>
                                <label class="btn btn-outline-secondary me-2 mb-2 px-3 py-2" for="size_<?= $v['size'] ?>">
                                    <?= $v['size'] ?>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-warning d-inline-block p-2">
                                <i class="fas fa-exclamation-circle"></i> Sản phẩm đang tạm hết hàng
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-4 w-25">
                    <label class="fw-bold mb-2">Số lượng:</label>
                    <input type="number" name="quantity" id="quantity" 
                           class="form-control text-center" 
                           value="1" min="1" max="100"
                           oninvalid="this.setCustomValidity('Kho chỉ còn ' + this.max + ' sản phẩm. Vui lòng chọn ít hơn!')"
                           oninput="this.setCustomValidity('')">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="action_type" value="add" 
                            class="btn btn-outline-danger btn-lg w-50 fw-bold" 
                            <?= empty($product['variants']) ? 'disabled' : '' ?>>
                        <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ
                    </button>
                    
                    <button type="submit" name="action_type" value="buy_now" 
                            class="btn btn-danger btn-lg w-50 fw-bold" 
                            <?= empty($product['variants']) ? 'disabled' : '' ?>>
                        <i class="fas fa-money-check-alt me-2"></i> MUA NGAY
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if(!empty($relatedProducts)): ?>
    <div class="mt-5 border-top pt-4">
        <h3 class="fw-bold mb-4 text-uppercase"><i class="fas fa-thumbs-up text-primary me-2"></i>Có thể bạn sẽ thích</h3>
        
        <div class="row">
            <?php foreach($relatedProducts as $rp): ?>
            <div class="col-md-3 col-6 mb-4">
                <div class="card h-100 shadow-sm card-hover border-0">
                    <?php if ($rp['old_price'] > $rp['price']): ?>
                        <?php $rpDiscount = round((($rp['old_price'] - $rp['price']) / $rp['old_price']) * 100); ?>
                        <div class="position-absolute top-0 start-0 badge bg-danger m-2">-<?= $rpDiscount ?>%</div>
                    <?php endif; ?>

                    <a href="index.php?controller=product&action=detail&id=<?= $rp['id'] ?>">
                        <img src="<?= htmlspecialchars($rp['image']) ?>" class="card-img-top p-3" alt="<?= htmlspecialchars($rp['name']) ?>" style="height: 200px; object-fit: contain;">
                    </a>
                    
                    <div class="card-body text-center d-flex flex-column">
                        <h6 class="card-title text-truncate">
                            <a href="index.php?controller=product&action=detail&id=<?= $rp['id'] ?>" class="text-decoration-none text-dark fw-bold">
                                <?= htmlspecialchars($rp['name']) ?>
                            </a>
                        </h6>
                        <div class="mt-auto">
                            <span class="text-danger fw-bold"><?= number_format($rp['price'], 0, ',', '.') ?> đ</span>
                            <?php if ($rp['old_price'] > $rp['price']): ?>
                                <br><small class="text-muted text-decoration-line-through"><?= number_format($rp['old_price'], 0, ',', '.') ?> đ</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .thumb-item:hover { transform: scale(1.05); transition: 0.2s; }
</style>

<script>
    // 1. Script đổi ảnh Gallery
    function changeImage(element, src) {
        const mainImg = document.getElementById('mainImage');
        mainImg.style.opacity = 0.5;
        setTimeout(() => {
            mainImg.src = src;
            mainImg.style.opacity = 1;
        }, 150);

        document.querySelectorAll('.thumb-item').forEach(el => {
            el.classList.remove('border-danger', 'border-2');
            el.classList.add('border');
        });
        element.classList.remove('border');
        element.classList.add('border-danger', 'border-2');
    }

    // 2. Script xử lý Tồn kho
    document.addEventListener('DOMContentLoaded', function() {
        const sizeRadios = document.querySelectorAll('.size-radio');
        const quantityInput = document.getElementById('quantity');
        const stockMessage = document.getElementById('stock-message');

        sizeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const stock = parseInt(this.getAttribute('data-stock'));
                quantityInput.max = stock;
                stockMessage.innerText = '(Còn ' + stock + ' sản phẩm)';
                if(parseInt(quantityInput.value) > stock) {
                    quantityInput.value = 1;
                }
            });
        });
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>