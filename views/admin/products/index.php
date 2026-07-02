<?php require_once 'views/admin/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Quản lý Sản phẩm</h3>
    <div>
        <form action="index.php" method="GET" class="d-inline-block me-2">
            <input type="hidden" name="controller" value="adminProduct">
            <input type="hidden" name="action" value="index">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <a href="index.php?controller=adminProduct&action=export" class="btn btn-success me-2">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        
        <a href="index.php?controller=adminProduct&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Kho hàng (Size - SL)</th> 
                    <th>Giá bán</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($products)): ?>
                    
                    <?php $stt = 0; ?>
                    
                    <?php foreach($products as $p): ?>
                    
                    <?php $stt++; ?>
                    
                    <tr>
                        <td class="text-center fw-bold"><?= $stt ?></td>
                        
                        <td><img src="<?= $p['image'] ?>" width="50"></td>
                        <td>
                            <strong><?= $p['name'] ?></strong><br>
                            <small><?= $p['brand_name'] ?> - <?= $p['cat_name'] ?></small>
                        </td>
                        
                        <td>
                            <?php 
                                if ($p['variant_info']) {
                                    $variants = explode(', ', $p['variant_info']);
                                    foreach($variants as $v) {
                                        echo "<span class='badge bg-secondary me-1'>Sz $v</span>";
                                    }
                                } else {
                                    echo "<span class='text-danger small'>Hết hàng</span>";
                                }
                            ?>
                        </td>

                        <td class="text-danger fw-bold"><?= number_format($p['price']) ?>đ</td>
                        <td>
                            <a href="index.php?controller=adminProduct&action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            
                            <a href="index.php?controller=adminProduct&action=delete&id=<?= $p['id'] ?>" onclick="return confirm('Chắc chắn xóa?')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">Không có sản phẩm nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/admin/layout/footer.php'; ?> 
</div></body></html>