<?php 
// 1. Khởi động session nếu chưa có
if (session_status() === PHP_SESSION_NONE) session_start(); 

// 2. Tính tổng số lượng sản phẩm trong giỏ hàng
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}

// 3. Lấy category hiện tại để tô đậm menu
$currentCategory = isset($_GET['category']) ? $_GET['category'] : '';

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport Kicks - Giày Bóng Đá Chính Hãng</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        /* Logo */
        .navbar-brand {
            font-size: 1.5rem;
            letter-spacing: 1px;
        }
        /* Menu Links */
        .nav-link {
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: #ffc107 !important; /* Màu vàng nổi bật khi hover/active */
        }
        /* Search Box */
        .input-group .form-control:focus {
            box-shadow: none;
            border-color: #adb5bd;
        }
        /* Dropdown */
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #dc3545; /* Đỏ khi hover item menu user */
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase d-flex align-items-center" href="index.php">
            <i class="fas fa-futbol me-2 text-warning"></i> <span>SPORT KICKS</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto ms-3">
                <li class="nav-item">
                    <a class="nav-link <?= ($currentCategory == 1) ? 'active' : '' ?>" href="index.php?controller=product&action=list&category=1">Cỏ nhân tạo</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= ($currentCategory == 2) ? 'active' : '' ?>" href="index.php?controller=product&action=list&category=2">Cỏ tự nhiên</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($currentCategory == 3) ? 'active' : '' ?>" href="index.php?controller=product&action=list&category=3">Giày Futsal</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($currentCategory == 4) ? 'active' : '' ?>" href="index.php?controller=product&action=list&category=4">Phụ kiện</a>
                </li>
            </ul>

            <form class="d-flex me-4" action="index.php" method="GET" style="min-width: 250px;">
                <input type="hidden" name="controller" value="product">
                <input type="hidden" name="action" value="search">
                <div class="input-group">
                    <input class="form-control form-control-sm border-0 rounded-start" type="search" name="keyword" placeholder="Tìm tên giày..." required>
                    <button class="btn btn-primary btn-sm rounded-end px-3" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav align-items-center gap-3">
                <li class="nav-item">
                    <a class="nav-link position-relative text-white" href="index.php?controller=cart&action=index">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <?php if($cartCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.65rem;">
                                <?= $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>

                <li class="border-start mx-2 d-none d-lg-block" style="height: 25px; border-color: rgba(255,255,255,0.2)!important;"></li>

                <?php if(isset($_SESSION['user'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['fullname']) ?>&background=random" class="rounded-circle me-2" width="30" height="30">
                            <span class="d-none d-lg-inline small fw-bold">Hi, <?= explode(' ', $_SESSION['user']['fullname'])[0] ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            
                            <?php if($_SESSION['user']['role'] == 'admin'): ?>
                                <li>
                                    <a class="dropdown-item text-danger fw-bold" href="index.php?controller=admin&action=dashboard">
                                        <i class="fas fa-tachometer-alt me-2"></i> Trang quản trị
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>

                            <li><a class="dropdown-item" href="index.php?controller=user&action=profile"><i class="fas fa-id-card me-2 text-secondary"></i> Hồ sơ cá nhân</a></li>
                            <li><a class="dropdown-item" href="index.php?controller=user&action=orders"><i class="fas fa-history me-2 text-secondary"></i> Lịch sử đơn hàng</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="index.php?controller=user&action=logout"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm fw-bold px-3 rounded-pill" href="index.php?controller=user&action=login">Đăng nhập</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>