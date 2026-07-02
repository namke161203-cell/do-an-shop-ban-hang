<?php require_once 'views/admin/layout/header.php'; ?>

<div class="card shadow mb-4">
    <div class="card-header bg-secondary text-white fw-bold d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-file-invoice me-2"></i> 
            <?php if (!empty($items)): ?>
                Chi tiết phiếu nhập: <?= date('H:i d/m/Y', strtotime($items[0]['created_at'])) ?>
            <?php else: ?>
                Chi tiết phiếu nhập
            <?php endif; ?>
        </span>
        <a href="index.php?controller=adminStock&action=history" class="btn btn-light btn-sm fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>
    <div class="card-body">
        
        <?php if (empty($items)): ?>
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-circle me-2"></i> Không tìm thấy dữ liệu cho phiếu nhập này!
                <br>Có thể định dạng thời gian bị sai lệch.
            </div>
        <?php else: ?>
        
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th width="80">Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th width="100">Size</th>
                            <th>SL nhập</th>
                            <th>Giá nhập</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $grandTotal = 0; 
                            $totalQty = 0;
                            $i = 1;
                        ?>
                        <?php foreach($items as $item): ?>
                            <?php 
                                $subtotal = $item['quantity'] * $item['import_price'];
                                $grandTotal += $subtotal;
                                $totalQty += $item['quantity'];
                            ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <?php if(!empty($item['image'])): ?>
                                        <img src="<?= $item['image'] ?>" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/50" class="img-thumbnail">
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($item['name']) ?></td>
                                <td class="text-center"><span class="badge bg-secondary"><?= $item['size'] ?></span></td>
                                <td class="text-center fw-bold"><?= $item['quantity'] ?></td>
                                <td class="text-end"><?= number_format($item['import_price'], 0, ',', '.') ?> đ</td>
                                <td class="text-end fw-bold text-success"><?= number_format($subtotal, 0, ',', '.') ?> đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold text-uppercase">Tổng cộng:</td>
                            <td class="text-center fw-bold text-danger"><?= $totalQty ?></td>
                            <td></td>
                            <td class="text-end fw-bold text-danger fs-5"><?= number_format($grandTotal, 0, ',', '.') ?> đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php 
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>