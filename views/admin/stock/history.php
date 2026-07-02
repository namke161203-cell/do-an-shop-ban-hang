<?php require_once 'views/admin/layout/header.php'; ?>

<div class="card shadow mb-4">
    <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
        <span><i class="fas fa-history me-2"></i> Lịch Sử Nhập Hàng</span>
        <div class="d-flex">
            <!-- Form Tìm kiếm -->
            <form action="index.php" method="GET" class="d-flex me-2">
                <input type="hidden" name="controller" value="adminStock">
                <input type="hidden" name="action" value="history">
                <div class="input-group input-group-sm">
                    <input type="text" name="keyword" class="form-control" placeholder="YYYY-MM-DD..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <a href="index.php?controller=adminStock&action=index" class="btn btn-primary btn-sm me-2">
                <i class="fas fa-plus me-1"></i> Nhập Mới
            </a>
            <a href="index.php?controller=adminStock&action=export" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i> Xuất Excel
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã Phiếu (Tự sinh)</th>
                        <th>Thời gian nhập</th>
                        <th>Số mặt hàng</th>
                        <th>Tổng số lượng</th>
                        <th>Tổng tiền hàng</th>
                        <th class="text-center">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($imports)): ?>
                        <tr><td colspan="6" class="text-center text-muted">Chưa có lịch sử nhập hàng nào.</td></tr>
                    <?php else: ?>
                        <?php foreach($imports as $imp): ?>
                            <?php 
                                // Tạo mã phiếu dựa trên ngày giờ nhập để nhìn chuyên nghiệp hơn
                                $code = 'NK-' . date('ymdHi', strtotime($imp['created_at'])); 
                            ?>
                            <tr>
                                <td class="fw-bold text-primary"><?= $code ?></td>
                                <td><?= date('H:i - d/m/Y', strtotime($imp['created_at'])) ?></td>
                                <td><?= $imp['items_count'] ?> loại</td>
                                <td><span class="badge bg-info text-dark"><?= $imp['total_qty'] ?> sp</span></td>
                                <td class="fw-bold text-danger"><?= number_format($imp['total_money'], 0, ',', '.') ?> đ</td>
                                <td class="text-center">
                                    <a href="index.php?controller=adminStock&action=detail&time=<?= urlencode($imp['created_at']) ?>" 
                                       class="btn btn-sm btn-outline-dark" title="Xem chi tiết hóa đơn">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'views/admin/layout/footer.php'; ?>