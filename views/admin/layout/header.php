<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Panel | Sport Kicks</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            overflow-x: hidden;
        }
        /* Sidebar cố định bên trái */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            color: #fff;
            padding-top: 20px;
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }
        /* Link trong sidebar */
        .sidebar a {
            text-decoration: none;
            color: #c2c7d0;
            padding: 12px 20px;
            display: block;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: 0.3s;
            cursor: pointer; /* Thêm con trỏ chuột để biết là click được */
        }
        .sidebar a:hover {
            background-color: #495057;
            color: #fff;
            padding-left: 25px;
        }
        .sidebar a.active {
            background-color: #0d6efd;
            color: #fff;
            font-weight: bold;
        }
        
        /* Style cho menu con (Submenu) */
        .submenu a {
            padding-left: 50px !important;
            font-size: 0.9rem;
            background-color: rgba(0, 0, 0, 0.2);
            border-bottom: none;
        }
        .submenu a:hover {
            padding-left: 55px !important;
            color: #ffc107 !important;
        }
        .submenu a.sub-active {
            color: #ffc107 !important;
            font-weight: bold;
        }

        .sidebar i { width: 25px; text-align: center; }
        
        .admin-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            min-height: 100vh;
        }
        
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar .link-text, .user-info, .fa-chevron-down { display: none; }
            .admin-content { margin-left: 70px; width: calc(100% - 70px); }
            .sidebar h4 { font-size: 0.8rem; }
        }
    </style>
</head>
<body>

<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    $ctrl = isset($_GET['controller']) ? $_GET['controller'] : 'admin';
    $act = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
?>

<div class="sidebar d-flex flex-column shadow">
    <h4 class="text-center fw-bold mb-4 text-white text-uppercase border-bottom border-secondary pb-3">
        <i class="fas fa-shield-alt text-warning"></i> ADMIN CP
    </h4>
    
    <?php if(isset($_SESSION['user'])): ?>
        <div class="user-info text-white text-center mb-4 pb-3 border-bottom border-secondary px-3">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['fullname']) ?>&background=random" class="rounded-circle mb-2" width="50">
            <br>
            <small class="text-muted">Xin chào,</small>
            <div class="fw-bold text-truncate"><?= $_SESSION['user']['fullname'] ?></div>
            <span class="badge bg-danger mt-1">Quản Trị Viên</span>
        </div>
    <?php endif; ?>
    
    <a href="index.php?controller=admin&action=dashboard" class="<?= ($ctrl=='admin' && $act=='dashboard') ? 'active' : '' ?>">
        <i class="fas fa-tachometer-alt"></i> <span class="link-text">Tổng quan</span>
    </a>
    
    <a href="index.php?controller=adminProduct&action=index" class="<?= ($ctrl=='adminProduct') ? 'active' : '' ?>">
        <i class="fas fa-box"></i> <span class="link-text">Sản phẩm</span>
    </a>

    <?php $isStock = ($ctrl == 'adminStock'); ?>
    <a href="#stockSubmenu" data-bs-toggle="collapse" 
       class="d-flex justify-content-between align-items-center <?= $isStock ? 'active' : '' ?>" 
       aria-expanded="<?= $isStock ? 'true' : 'false' ?>" 
       role="button">
        <span>
            <i class="fas fa-warehouse"></i> <span class="link-text">Quản lý nhập kho</span>
        </span>
        <i class="fas fa-chevron-down" style="font-size: 0.8em;"></i>
    </a>
    
    <div class="collapse submenu <?= $isStock ? 'show' : '' ?>" id="stockSubmenu">
        <a href="index.php?controller=adminStock&action=index" 
           class="<?= ($isStock && $act=='index') ? 'sub-active' : '' ?>">
            <i class="fas fa-plus-circle me-2" style="font-size: 0.8em;"></i> <span class="link-text">Nhập Hàng Mới</span>
        </a>
        <a href="index.php?controller=adminStock&action=history" 
           class="<?= ($isStock && ($act=='history' || $act=='detail')) ? 'sub-active' : '' ?>">
            <i class="fas fa-history me-2" style="font-size: 0.8em;"></i> <span class="link-text">Lịch Sử Nhập</span>
        </a>
    </div>

    <a href="index.php?controller=adminBrand&action=index" class="<?= ($ctrl=='adminBrand') ? 'active' : '' ?>">
        <i class="fas fa-tags"></i> <span class="link-text">Thương hiệu</span>
    </a>
    
    <a href="index.php?controller=adminOrder&action=index" class="<?= ($ctrl=='adminOrder') ? 'active' : '' ?>">
        <i class="fas fa-shopping-cart"></i> <span class="link-text">Đơn hàng</span>
    </a>
    
    <a href="index.php?controller=adminUser&action=index" class="<?= ($ctrl=='adminUser') ? 'active' : '' ?>">
        <i class="fas fa-users"></i> <span class="link-text">Thành viên</span>
    </a>
    
    <div class="mt-auto border-top border-secondary pt-2">
        <a href="index.php" class="text-info">
            <i class="fas fa-globe"></i> <span class="link-text">Xem Website</span>
        </a>
        <a href="index.php?controller=user&action=logout" class="text-danger fw-bold">
            <i class="fas fa-sign-out-alt"></i> <span class="link-text">Đăng xuất</span>
        </a>
    </div>
</div>

<div class="admin-content">
    <nav class="navbar navbar-light bg-white shadow-sm rounded mb-4 px-3 py-2 d-flex justify-content-between align-items-center">
        <span class="fw-bold text-secondary text-uppercase">
            <?php 
                if($ctrl == 'adminProduct') echo 'Quản lý Sản Phẩm';
                elseif($ctrl == 'adminStock' && $act == 'index') echo 'Nhập Kho Sản Phẩm';
                elseif($ctrl == 'adminStock' && ($act == 'history' || $act == 'detail')) echo 'Lịch Sử Nhập Hàng';
                elseif($ctrl == 'adminBrand') echo 'Quản lý Thương Hiệu'; 
                elseif($ctrl == 'adminOrder') echo 'Quản lý Đơn Hàng';
                elseif($ctrl == 'adminUser') echo 'Quản lý Thành Viên';
                else echo 'Dashboard';
            ?>
        </span>
        <span class="text-muted small"><i class="far fa-clock me-1"></i> <?= date('d/m/Y') ?></span>
    </nav>