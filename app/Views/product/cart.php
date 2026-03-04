<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng của bạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .cart-box { background: #fff; border-radius: 12px; padding: 20px; }
        /* Xóa dấu mũi tên mặc định của ô number cho đẹp */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { opacity: 1; }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <h2 class="text-primary fw-bold text-center mb-4">🛒 Giỏ Hàng Của Bạn</h2>

        <div class="cart-box shadow-sm border">
            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                
                <table class="table align-middle text-center table-hover">
                    <thead class="table-light">
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
                            // Xử lý biến tồn kho để tránh lỗi
                            $kho = isset($item['stock']) ? $item['stock'] : 99;
                        ?>
                        <tr>
                            <td><img src="<?= $item['image'] ?>" width="70" class="img-thumbnail rounded"></td>
                            <td class="text-start fw-bold"><?= htmlspecialchars($item['name']) ?></td>
                            <td class="text-danger fw-bold"><?= number_format($item['price']) ?> đ</td>
                            
                            <td>
                                <input type="number" 
                                       class="form-control text-center mx-auto fw-bold" 
                                       style="width: 80px; border: 1px solid #ced4da; border-radius: 8px;"
                                       value="<?= $item['quantity'] ?>" 
                                       min="1" max="<?= $kho ?>"
                                       data-id="<?= $id ?>"
                                       data-price="<?= $item['price'] ?>"
                                       onchange="updateCartUI(this)">
                            </td>

                            <td class="text-danger fw-bold fs-5 item-total" id="row-total-<?= $id ?>">
                                <?= number_format($thanhTien) ?> đ
                            </td>
                            
                            <td>
                                <a href="index.php?action=remove_from_cart&id=<?= $id ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bỏ món này ra khỏi giỏ hả?')">❌ Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-5 text-secondary pt-4">Tổng thanh toán:</td>
                            <td class="text-danger fw-bold fs-4 pt-4" id="grand-total"><?= number_format($tongTien) ?> đ</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="d-flex justify-content-between mt-4">
                    <a href="index.php" class="btn btn-secondary fs-5 px-4 rounded-pill">🔙 Mua thêm</a>
                    <a href="index.php?action=checkout" class="btn btn-success fw-bold fs-5 px-5 rounded-pill shadow-sm">💳 Thanh Toán</a>
                </div>

            <?php else: ?>
                <div class="text-center py-5">
                    <h4 class="text-muted mb-3">Giỏ hàng trống trơn! 😢</h4>
                    <p class="text-secondary">Chưa có bé Figure nào được chọn</p>
                    <a href="index.php" class="btn btn-primary mt-2 px-4 rounded-pill">Đến cửa hàng ngay</a>
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

            // 1. Chặn khách nhập tay số tào lao hoặc lố kho
            if (qty > maxStock) {
                alert('Món này trong kho chỉ còn tối đa ' + maxStock + ' sản phẩm thôi!');
                qty = maxStock;
                input.value = maxStock;
            } else if (qty < 1 || isNaN(qty)) {
                qty = 1;
                input.value = 1;
            }

            // 2. Tự động nhân tiền và in ra dòng đó (Dùng en-US để có dấu phẩy giống PHP)
            let rowTotal = price * qty;
            document.getElementById('row-total-' + id).innerText = rowTotal.toLocaleString('en-US') + ' đ';

            // 3. Tự động tính lại Tổng Cộng Cuối Cùng
            let allTotals = document.querySelectorAll('.item-total');
            let grandTotal = 0;
            allTotals.forEach(function(el) {
                // Lọc bỏ chữ "đ" và dấu phẩy, chỉ lấy đúng con số để cộng
                let val = el.innerText.replace(/[^0-9]/g, '');
                grandTotal += parseInt(val);
            });
            document.getElementById('grand-total').innerText = grandTotal.toLocaleString('en-US') + ' đ';

            // 4. Giao tiếp ngầm (AJAX fetch) xuống PHP để lưu số lượng vào Session
            fetch(`index.php?action=update_cart&id=${id}&quantity=${qty}`)
                .then(response => {
                    console.log("Đã cập nhật giỏ hàng tàng hình thành công!");
                })
                .catch(error => console.error('Lỗi mạng:', error));
        }
    </script>
</body>
</html>