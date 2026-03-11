<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng của bạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* --- HIỆU ỨNG NAVBAR TỐI GIẢN --- */
        .shop-navbar { background-color: #ffe4e8; box-shadow: 0 4px 15px rgba(255, 182, 193, 0.4); padding: 15px 0; border-bottom: 2px solid #ffc2d1; }
        .brand-logo { font-size: 1.8rem; font-weight: 900; background: linear-gradient(to right, #ff0844, #ffb199); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none; letter-spacing: 0.5px; }
        /* --- GIAO DIỆN GIỎ HÀNG TONE HỒNG --- */
        body { background-color: #fff0f3; } /* Nền hồng siêu nhạt */
        
        .cart-box { 
            background: #fff; 
            border-radius: 15px; 
            padding: 30px; 
            box-shadow: 0 10px 30px rgba(255, 182, 193, 0.4); 
            border: none; 
        }
        
        .cart-title { 
            color: #ff4d6d; 
            font-weight: 900; 
            text-shadow: 1px 1px 2px rgba(255, 182, 193, 0.3); 
        }

        /* Đầu bảng Gradient Hồng chống tàng hình */
        .table-pink-header { background: linear-gradient(to right, #ff758f, #ff4d6d) !important; }
        .table-pink-header th { background-color: transparent !important; color: white !important; border-bottom: none; padding: 15px; }

        /* Nút bấm kute */
        .btn-pink { background: linear-gradient(to right, #ff758f, #ff4d6d); color: white; border: none; transition: all 0.3s ease; font-weight: bold; }
        .btn-pink:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(255, 77, 109, 0.4); color: white; }
        
        .btn-pink-outline { border: 2px solid #ff758f; color: #ff758f; background: transparent; font-weight: bold; transition: all 0.3s ease; }
        .btn-pink-outline:hover { background-color: #ff758f; color: white; transform: translateY(-3px); }

        /* Khung nhập số lượng màu mè xíu */
        .input-qty { border: 2px solid #ffb3c6 !important; background-color: #fff9fa !important; color: #ff4d6d !important; }
        
        /* Xóa dấu mũi tên mặc định của ô number cho đẹp */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { opacity: 1; }
    </style>
</head>
<body>
    <nav class="navbar shop-navbar sticky-top mb-4">
        <div class="container text-center text-md-start">
            <a class="brand-logo" href="index.php">✨ Ma's Store</a>
        </div>
    </nav>
    <div class="container mt-5 mb-5">
        <h2 class="cart-title text-center mb-4">🛒 Giỏ Hàng Của Bạn</h2>

        <div class="cart-box">
            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                
                <table class="table align-middle text-center table-hover">
                    <thead class="table-pink-header">
                        <tr>
                            <th>Ảnh</th>
                            <th class="text-start">Tên Figure</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $tongTien = 0;
                        foreach($_SESSION['cart'] as $id => $item): 
                            $thanhTien = $item['price'] * $item['quantity'];
                            $tongTien += $thanhTien; 
                            $kho = isset($item['stock']) ? $item['stock'] : 99;
                        ?>
                        <tr>
                            <td><img src="<?= $item['image'] ?>" width="80" class="img-thumbnail rounded" style="border-color: #ffc2d1;"></td>
                            <td class="text-start fw-bold" style="color: #d81b60;"><?= htmlspecialchars($item['name']) ?></td>
                            <td class="text-danger fw-bold"><?= number_format($item['price']) ?> đ</td>
                            
                            <td>
                                <input type="number" 
                                    class="form-control text-center mx-auto fw-bold input-qty" 
                                    style="width: 80px; border-radius: 8px;"
                                    value="<?= isset($item['quantity']) ? $item['quantity'] : 1 ?>" 
                                    min="1" max="<?= isset($item['stock']) ? $item['stock'] : 99 ?>"
                                    data-id="<?= $id ?>"
                                    data-price="<?= $item['price'] ?>"
                                    onchange="updateCartUI(this)">
                            </td>

                            <td class="text-danger fw-bold fs-5 item-total" id="row-total-<?= $id ?>">
                                <?= number_format($thanhTien) ?> đ
                            </td>
                            
                            <td>
                                <a href="index.php?action=remove_from_cart&id=<?= $id ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Nỡ lòng nào bỏ bé này ra khỏi giỏ hả?')">❌ Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-5 text-secondary pt-4 border-0">Tổng thanh toán:</td>
                            <td class="text-danger fw-bold fs-3 pt-4 border-0" id="grand-total"><?= number_format($tongTien) ?> đ</td>
                            <td class="border-0"></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="d-flex justify-content-between mt-5">
                    <a href="index.php" class="btn btn-pink-outline fs-5 px-4 rounded-pill shadow-sm">🔙 Mua thêm</a>
                    <a href="index.php?action=checkout" class="btn btn-pink fw-bold fs-5 px-5 rounded-pill shadow">💳 Thanh Toán Ngay</a>
                </div>

            <?php else: ?>
                <div class="text-center py-5">
                    <h4 class="text-danger fw-bold mb-3">Giỏ hàng trống trơn! 😢</h4>
                    <p class="text-secondary fs-5">Chưa có bé Waifu nào được rước về nhà</p>
                    <a href="index.php" class="btn btn-pink mt-3 px-5 py-2 rounded-pill fs-5 shadow">Đến cửa hàng ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateCartUI(input) {
            let id = input.getAttribute('data-id');
            let price = parseInt(input.getAttribute('data-price'));
            let qty = parseInt(input.value);
            let maxStock = parseInt(input.getAttribute('max'));

            if (qty > maxStock) {
                alert('Món này trong kho chỉ còn tối đa ' + maxStock + ' sản phẩm thôi!');
                qty = maxStock;
                input.value = maxStock;
            } else if (qty < 1 || isNaN(qty)) {
                qty = 1;
                input.value = 1;
            }

            let rowTotal = price * qty;
            document.getElementById('row-total-' + id).innerText = rowTotal.toLocaleString('en-US') + ' đ';

            let allTotals = document.querySelectorAll('.item-total');
            let grandTotal = 0;
            allTotals.forEach(function(el) {
                let val = el.innerText.replace(/[^0-9]/g, '');
                grandTotal += parseInt(val);
            });
            document.getElementById('grand-total').innerText = grandTotal.toLocaleString('en-US') + ' đ';

            fetch(`index.php?action=update_cart&id=${id}&quantity=${qty}`)
                .then(response => { console.log("Đã cập nhật giỏ hàng tàng hình thành công!"); })
                .catch(error => console.error('Lỗi mạng:', error));
        }
    </script>
</body>
</html>