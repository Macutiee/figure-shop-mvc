<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh Toán - Ma's Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .checkout-box { background: #fff; border-radius: 12px; padding: 30px; }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <h2 class="text-primary fw-bold text-center mb-4">🚀 Hoàn Tất Đơn Hàng</h2>

                <div class="checkout-box shadow-sm border">
                    <form action="index.php?action=process_checkout" method="POST">
                        
                        <h4 class="mb-3 text-secondary">📍 Thông tin giao hàng</h4>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên người nhận</label>
                            <input type="text" name="fullname" class="form-control form-control-lg" placeholder="Nhập tên của bạn..." required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control form-control-lg" placeholder="Ví dụ: 0987654321" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Địa chỉ giao hàng chi tiết</label>
                            <textarea name="address" class="form-control form-control-lg" rows="3" placeholder="Số nhà, Tên đường, Phường/Xã, Quận/Huyện..." required></textarea>
                        </div>

                        <hr class="my-4">

                        <h4 class="mb-3 text-secondary">🛍️ Tóm tắt đơn hàng</h4>
                        <div class="bg-light p-3 rounded mb-4">
                            <?php 
                                $tongTien = 0;
                                foreach($_SESSION['cart'] as $item) {
                                    $tongTien += $item['price'] * $item['quantity'];
                                }
                            ?>
                            <div class="d-flex justify-content-between fs-5">
                                <span>Tổng tiền thanh toán (COD):</span>
                                <span class="fw-bold text-danger"><?= number_format($tongTien) ?> đ</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="index.php?action=cart" class="text-decoration-none text-secondary fs-5">🔙 Quay lại giỏ hàng</a>
                            <button type="submit" class="btn btn-danger btn-lg fw-bold px-5 py-3 shadow"> ĐẶT HÀNG NGAY</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</body>
</html>