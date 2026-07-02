<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #<?= $order['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Times New Roman', Times, serif; 
        }
        .invoice-container {
            background: white;
            max-width: 800px; 
            margin: 30px auto;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    
        @media print {
            body { background: white; }
            .invoice-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
            }
            .no-print {
                display: none !important; /* Ẩn nút in và đóng khi in */
            }
            @page { margin: 2cm; }
        }
        .shop-info h4 { color: #0d6efd; font-weight: bold; text-transform: uppercase; }
        .invoice-title { font-size: 2rem; font-weight: bold; color: #333; text-transform: uppercase; }
        .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; }
    </style>
</head>
<body>

    <div class="invoice-container">
        <div class="d-flex justify-content-between mb-4 no-print">
            <button onclick="window.close()" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Đóng
            </button>
            <button onclick="window.print()" class="btn btn-primary btn-lg shadow-sm">
                <i class="fa-solid fa-print"></i> In Hóa Đơn
            </button>
        </div>

        <div class="row mb-4 align-items-center">
            <div class="col-7">
                <div class="shop-info">
                    <h4>CỬA HÀNG GIÀY BÓNG ĐÁ SPORT KICKS</h4> <p class="mb-1"><i class="fa-solid fa-location-dot me-2"></i>Địa chỉ: 54 Triều Khúc, Thanh Xuân, Hà Nội</p>
                    <p class="mb-1"><i class="fa-solid fa-phone me-2"></i>Hotline: 0332755735</p>
                    <p class="mb-0"><i class="fa-solid fa-envelope me-2"></i>Email: contact@sportkicks.com</p>
                </div>
            </div>
            <div class="col-5 text-end">
                <div class="invoice-title">HÓA ĐƠN</div>
                <p class="mb-1"><strong>Mã đơn:</strong> #<?= $order['id'] ?></p>
                <p class="mb-0"><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                <p class="mt-2 badge bg-success fs-6"><?= strtoupper($order['status']) ?></p>
            </div>
        </div>

        <hr class="my-4">

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="fw-bold text-primary mb-3">THÔNG TIN KHÁCH HÀNG</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><strong>Họ và tên:</strong> <?= $order['fullname'] ?? $order['customer_name'] ?? 'Khách lẻ' ?></li>
                    <li class="mb-2"><strong>Số điện thoại:</strong> 
                        <?= !empty($order['phone']) ? $order['phone'] : ($order['customer_phone'] ?? '---') ?>
                    </li>
                    <li class="mb-2"><strong>Địa chỉ giao hàng:</strong> <?= $order['address'] ?></li>
                </ul>
            </div>
        </div>

        <table class="table table-bordered align-middle mt-4">
            <thead>
                <tr class="text-center">
                    <th style="width: 50px;">STT</th>
                    <th>Tên sản phẩm</th>
                    <th style="width: 80px;">Size</th>
                    <th style="width: 120px;">Đơn giá</th>
                    <th style="width: 80px;">SL</th>
                    <th style="width: 130px;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $stt = 1; 
                    foreach($details as $item): 
                ?>
                <tr>
                    <td class="text-center"><?= $stt++ ?></td>
                    <td><?= $item['name'] ?></td>
                    <td class="text-center"><span class="badge bg-light text-dark border"><?= $item['size'] ?></span></td>
                    <td class="text-end"><?= number_format($item['price']) ?> </td>
                    <td class="text-center fw-bold"><?= $item['quantity'] ?></td>
                    <td class="text-end fw-bold"><?= number_format($item['price'] * $item['quantity']) ?> đ</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end fw-bold fs-5">TỔNG CỘNG THANH TOÁN:</td>
                    <td class="text-end fw-bold fs-5 text-danger"><?= number_format($order['total_money']) ?> đ</td>
                </tr>
            </tfoot>
        </table>

        <div class="row mt-5 pt-4 text-center">
            <div class="col-6">
                <p class="fw-bold mb-5">Người mua hàng</p>
                <small class="fst-italic">(Ký, ghi rõ họ tên)</small>
            </div>
            <div class="col-6">
                <p class="fw-bold mb-5">Người bán hàng</p>
                <small class="fst-italic">(Ký, ghi rõ họ tên)</small>
            </div>
        </div>

        <div class="text-center mt-5 pt-3 border-top no-print">
            <small class="text-muted">Cảm ơn quý khách đã mua hàng tại Shop!</small>
        </div>
    </div>

</body>
</html>