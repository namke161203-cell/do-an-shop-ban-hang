<?php require_once 'views/admin/layout/header.php'; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white fw-bold">
                <?php if(isset($_GET['edit_id'])): 
                    $editBrand = null;
                    foreach($brands as $b) { if($b['id'] == $_GET['edit_id']) $editBrand = $b; }
                ?>
                    <i class="fas fa-edit me-2"></i> Sửa Thương Hiệu
                <?php else: ?>
                    <i class="fas fa-plus-circle me-2"></i> Thêm Thương Hiệu
                <?php endif; ?>
            </div>
            <div class="card-body">
                <form action="index.php?controller=adminBrand&action=<?= isset($editBrand) ? 'update' : 'store' ?>" method="POST">
                    
                    <?php if(isset($editBrand)): ?>
                        <input type="hidden" name="id" value="<?= $editBrand['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="fw-bold">Tên thương hiệu</label>
                        <input type="text" name="name" class="form-control" required 
                               value="<?= isset($editBrand) ? htmlspecialchars($editBrand['name']) : '' ?>" 
                               placeholder="VD: Nike, Adidas...">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success fw-bold">
                            <i class="fas fa-save me-2"></i> Lưu Lại
                        </button>
                        <?php if(isset($editBrand)): ?>
                            <a href="index.php?controller=adminBrand&action=index" class="btn btn-secondary mt-2">Hủy</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                <div>
                    <h3>Quản lý Thương hiệu</h3>
                    <!-- Form Tìm kiếm Thương Hiệu -->
                    <form action="index.php" method="GET" class="d-inline-block me-2">
                        <input type="hidden" name="controller" value="adminBrand">
                        <input type="hidden" name="action" value="index">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm tên thương hiệu..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
                
                <a href="index.php?controller=adminBrand&action=export" class="btn btn-success me-2">
                    <i class="fas fa-file-excel me-1"></i> Xuất Excel
                </a>
            </div>
            
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Tên thương hiệu</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($brands as $b): ?>
                        <tr>
                            <td><?= $b['id'] ?></td>
                            <td class="fw-bold text-primary"><?= htmlspecialchars($b['name']) ?></td>
                            <td class="text-end">
                                <a href="index.php?controller=adminBrand&action=showProducts&id=<?= $b['id'] ?>" 
                                   class="btn btn-sm btn-info text-white me-1" title="Xem danh sách sản phẩm">
                                    <i class="fas fa-eye"></i> Xem SP
                                </a>

                                <a href="index.php?controller=adminBrand&action=index&edit_id=<?= $b['id'] ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <a href="index.php?controller=adminBrand&action=delete&id=<?= $b['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa thương hiệu này?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>