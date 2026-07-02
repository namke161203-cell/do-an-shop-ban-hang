<?php require_once 'views/admin/layout/header.php'; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-primary">
            Sản phẩm thuộc thương hiệu: <span class="text-danger"><?= htmlspecialchars($brand['name']) ?></span>
        </h6>
        <a href="index.php?controller=adminBrand&action=index" class="btn btn-secondary btn-sm">Quay lại</a>
    </div>
    <div class="card-body">
        <?php if (empty($products)): ?>
            <div class="alert alert-warning text-center">Chưa có sản phẩm nào thuộc thương hiệu này!</div>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá bán</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><img src="<?= $p['image'] ?>" width="50"></td>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= number_format($p['price']) ?>đ</td>
                            <td>
                                <a href="index.php?controller=adminProduct&action=edit&id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>