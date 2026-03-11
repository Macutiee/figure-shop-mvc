<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* --- HIỆU ỨNG NAVBAR TỐI GIẢN --- */
        .shop-navbar { background-color: #ffe4e8; box-shadow: 0 4px 15px rgba(255, 182, 193, 0.4); padding: 15px 0; border-bottom: 2px solid #ffc2d1; }
        .brand-logo { font-size: 1.8rem; font-weight: 900; background: linear-gradient(to right, #ff0844, #ffb199); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none; letter-spacing: 0.5px; }
        /* --- GIAO DIỆN THANH TOÁN TONE HỒNG --- */
        body { background-color: #fff0f3; }
        
        .checkout-box { 
            background: #fff; 
            border-radius: 15px; 
            padding: 30px; 
            box-shadow: 0 10px 30px rgba(255, 182, 193, 0.4); 
            border: none; 
            height: 100%;
        }
        
        .page-title { color: #ff4d6d; font-weight: 900; text-shadow: 1px 1px 2px rgba(255, 182, 193, 0.3); }
        .section-title { color: #ff758f; font-weight: bold; border-bottom: 2px dashed #ffc2d1; padding-bottom: 10px; margin-bottom: 20px; }

        /* Form Control kute */
        .form-control { border: 2px solid #ffe4e8; border-radius: 10px; padding: 12px; transition: all 0.3s; }
        .form-control:focus { border-color: #ff758f; box-shadow: 0 0 10px rgba(255, 117, 143, 0.2); outline: none; }
        .form-label { font-weight: 600; color: #d81b60; }

        /* Nút chốt đơn */
        .btn-pink { background: linear-gradient(to right, #ff758f, #ff4d6d); color: white; border: none; transition: all 0.3s ease; font-weight: bold; }
        .btn-pink:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(255, 77, 109, 0.4); color: white; }
        
        .btn-pink-outline { border: 2px solid #ff758f; color: #ff758f; background: transparent; font-weight: bold; transition: all 0.3s ease; }
        .btn-pink-outline:hover { background-color: #ff758f; color: white; transform: translateY(-3px); }

        /* Hóa đơn bên phải */
        .bill-item { border-bottom: 1px solid #ffe4e8; padding-bottom: 15px; margin-bottom: 15px; }
        .bill-item:last-child { border-bottom: none; }
        .total-box { background-color: #fff9fa; border-radius: 10px; padding: 20px; border: 2px dashed #ffb3c6; }
    </style>
</head>
<body>
    <nav class="navbar shop-navbar sticky-top mb-4">
        <div class="container text-center text-md-start">
            <a class="brand-logo" href="index.php">✨ Ma's Store</a>
        </div>
    </nav>
    <div class="container mt-5 mb-5">
        <h2 class="page-title text-center mb-5">💳 Hoàn Tất Đơn Hàng</h2>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="checkout-box">
                    <h4 class="section-title">📍 Thông tin nhận hàng</h4>
                    
                    <form action="index.php?action=process_checkout" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" placeholder="Nhập họ tên của bạn..." required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control" placeholder="Nhập số điện thoại của bạn..." required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <textarea name="customer_address" class="form-control" rows="3" placeholder="Ghi rõ số nhà, đường, phường/xã, quận/huyện..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Ghi chú cho Shop (Không bắt buộc)</label>
                            <textarea name="customer_note" class="form-control" rows="2" placeholder="Ví dụ: Giao giờ hành chính, bọc kỹ xíu shop ơi..."></textarea>
                        </div>

                        <div class="d-flex gap-3">
                            <a href="index.php?action=cart" class="btn btn-pink-outline py-3 px-4 rounded-pill w-100 text-center text-decoration-none">🔙 Quay lại Giỏ hàng</a>
                            <button type="submit" class="btn btn-pink py-3 rounded-pill w-100 fs-5 shadow"> ĐẶT NGAY!</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="checkout-box">
                    <h4 class="section-title">🛍️ Đơn hàng của bạn</h4>
                    
                    <?php 
                    $tongTien = 0;
                    if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): 
                        foreach($_SESSION['cart'] as $item): 
                            $thanhTien = $item['price'] * $item['quantity'];
                            $tongTien += $thanhTien;
                    ?>
                        <div class="bill-item d-flex align-items-center gap-3">
                            <img src="<?= $item['image'] ?>" class="rounded shadow-sm" style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #ffc2d1;">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1" style="color: #d81b60; font-size: 0.95rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= htmlspecialchars($item['name']) ?></h6>
                                <span class="text-muted small">SL: <?= $item['quantity'] ?> x <span class="text-danger fw-bold"><?= number_format($item['price']) ?>đ</span></span>
                            </div>
                            <div class="fw-bold text-danger">
                                <?= number_format($thanhTien) ?>đ
                            </div>
                        </div>
                    <?php 
                        endforeach; 
                    else:
                    ?>
                        <p class="text-center text-danger mt-4">Trời ơi giỏ hàng trống trơn kìa!</p>
                    <?php endif; ?>

                    <div class="total-box mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold text-secondary">Tạm tính:</span>
                            <span class="fw-bold text-dark"><?= number_format($tongTien) ?> đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom border-danger pb-3">
                            <span class="fw-bold text-secondary">Phí vận chuyển:</span>
                            <span class="fw-bold text-success">Miễn phí (Freeship) 💖</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-5 text-dark">TỔNG CỘNG:</span>
                            <span class="fw-bold fs-3 text-danger"><?= number_format($tongTien) ?> đ</span>
                        </div>
                        <div class="text-center mt-3 small text-muted">
                            (Thanh toán khi nhận hàng - COD)
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>