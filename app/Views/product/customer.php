<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Ma's Store - Mô hình chính hãng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Nền web hơi xám nhẹ để làm nổi bật thẻ sản phẩm màu trắng */
        body { background-color: #f4f6f9; }
        
        /* Hiệu ứng thẻ sản phẩm: bo góc, viền mờ, khi chuột vào thì nổi lên */
        .product-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            overflow: hidden;
            background: #fff;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
        
        /* Cố định chiều cao ảnh để khung không bị thò thụt */
        .product-img {
            height: 280px;
            object-fit: cover;
            width: 100%;
            cursor: pointer;
            transition: transform 0.5s ease;
        }
        .product-card:hover .product-img {
            transform: scale(1.05); /* Rê chuột vào ảnh tự zoom nhẹ sương sương */
        }
        
        /* Giới hạn tên sản phẩm tối đa 2 dòng, dài quá tự ra dấu ... */
        .figure-title {
            font-size: 1.1rem;
            font-weight: 600;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 3rem;
            color: #2b2b2b;
        }
        
        /* Giá tiền đỏ chót, bự chà bá */
        .price-text {
            color: #ff424e;
            font-weight: 700;
            font-size: 1.3rem;
        }

        /* Tem nhãn (Badge) đè lên góc ảnh */
        .badge-brand { position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); font-size: 0.8rem; }
        .badge-stock { position: absolute; top: 10px; left: 10px; font-size: 0.8rem; }
        /* --- HIỆU ỨNG CHO NAVBAR MỚI --- */
        .custom-navbar {
            background: rgba(255, 255, 255, 0.95); /* Nền trắng hơi trong suốt */
            backdrop-filter: blur(10px); /* Hiệu ứng kính mờ xịn xò */
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); /* Bóng đổ nhẹ nhàng */
            padding: 12px 0;
        }
        
        /* Chữ Logo chuyển màu Gradient */
        .logo-text {
            font-weight: 800;
            font-size: 2rem;
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.5px;
        }

        /* Thanh tìm kiếm liền khối */
        .search-input {
            border-radius: 50px 0 0 50px !important;
            padding: 10px 20px;
            border: 2px solid #ffe3e3;
            box-shadow: none !important;
            transition: all 0.3s;
        }
        .search-input:focus { border-color: #ff4b2b; }
        .search-btn {
            border-radius: 0 50px 50px 0 !important;
            background: #ff4b2b;
            color: white;
            border: 2px solid #ff4b2b;
            padding: 0 25px;
            transition: all 0.3s;
            font-weight: bold;
        }
        .search-btn:hover { background: #ff416c; color: white; }

        /* Icon Giỏ hàng & Admin */
        .cart-wrapper {
            font-size: 1.8rem;
            text-decoration: none;
            transition: transform 0.2s;
            display: inline-block;
        }
        .cart-wrapper:hover { transform: scale(1.15); } /* Rê chuột vào phình to ra */
        
        .cart-badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
            border: 2px solid #fff; /* Viền trắng tách biệt với icon */
        }

        .admin-link {
            font-size: 1.5rem;
            text-decoration: none;
            transition: transform 0.5s ease; /* Xoay mượt mà trong 0.5s */
            color: #555;
        }
        .admin-link:hover { transform: rotate(180deg); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg custom-navbar sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            
            <a class="navbar-brand logo-text" href="index.php">
                ✨ Ma's Store
            </a>
            
            <form action="index.php" method="GET" class="d-flex mx-auto" style="width: 45%; max-width: 600px;">
                <div class="input-group">
                    <input type="text" name="search" class="form-control search-input" 
                           placeholder="🔍 Bạn muốn rước Waifu nào về nhà?..." 
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button class="btn search-btn" type="submit">Tìm kiếm</button>
                </div>
                <?php if(isset($_GET['search']) && $_GET['search'] != ''): ?>
                    <a href="index.php" class="btn btn-light rounded-circle ms-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;" title="Hủy tìm kiếm">❌</a>
                <?php endif; ?>
            </form>

            <div class="d-flex align-items-center gap-4">
                
                <?php 
                    $cart_count = 0;
                    if(isset($_SESSION['cart'])) {
                        foreach($_SESSION['cart'] as $item) {
                            $cart_count += $item['quantity'];
                        }
                    }
                ?>
                <a href="index.php?action=cart" class="cart-wrapper position-relative text-dark" title="Xem giỏ hàng">
                    🛒
                    <?php if($cart_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge shadow">
                            <?= $cart_count ?>
                        </span>
                    <?php endif; ?>
                </a>

                <a href="index.php?action=admin" class="admin-link text-secondary" title="Vào trang quản trị">
                    ⚙️
                </a>
            </div>

        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="p-5 text-center bg-dark text-white rounded-4 shadow" style="background: linear-gradient(45deg, #1a2a6c, #b21f1f, #fdbb2d);">
            <h1 class="fw-bold display-5">Thiên Đường Figure Chính Hãng</h1>
            <p class="lead">Săn lùng những mô hình cực phẩm với giá tốt nhất thị trường</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
            
            <?php foreach ($products as $row): ?>
            <div class="col">
                <div class="card h-100 product-card shadow-sm position-relative">
                    
                    <span class="badge badge-brand rounded-pill"><?= htmlspecialchars($row['brand']) ?></span>
                    <?php if ($row['stock'] > 0): ?>
                        <span class="badge badge-stock bg-success rounded-pill">Còn <?= $row['stock'] ?></span>
                    <?php else: ?>
                        <span class="badge badge-stock bg-secondary rounded-pill">Hết hàng</span>
                    <?php endif; ?>

                    <div class="overflow-hidden">
                        <img src="<?= $row['image'] ?>" class="card-img-top product-img" 
                             onclick="viewImage('<?= $row['image'] ?>', '<?= htmlspecialchars($row['name']) ?>')">
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title figure-title" title="<?= htmlspecialchars($row['name']) ?>">
                            <?= htmlspecialchars($row['name']) ?>
                        </h5>
                        <p class="card-text price-text mt-auto mb-3"><?= number_format($row['price']) ?> đ</p>
                        
                        <?php if ($row['stock'] > 0): ?>
    <div class="d-flex gap-2 mt-auto">
        <a href="index.php?action=buy_now&id=<?= $row['id'] ?>" class="btn btn-danger flex-grow-1 fw-bold py-2 rounded-3 text-nowrap shadow-sm" title="Mua ngay và thanh toán">
            Mua ngay
        </a>
        
        <a href="index.php?action=add_to_cart&id=<?= $row['id'] ?>" class="btn btn-outline-primary py-2 px-3 rounded-3 d-flex align-items-center justify-content-center" title="Thêm vào giỏ hàng">
            <span class="fs-5">🛒</span>
        </a>
    </div>
<?php else: ?>
    <button class="btn btn-secondary w-100 fw-bold py-2 rounded-3 mt-auto" disabled>
        ❌ Tạm Hết Hàng
    </button>
<?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered"> 
            <div class="modal-content bg-transparent border-0"> 
                <div class="modal-body text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                    <img id="modalImageSrc" src="" class="img-fluid rounded shadow-lg d-block mx-auto" style="max-height: 80vh;">
                    <h5 id="modalImageTitle" class="text-white mt-3 bg-dark d-inline-block px-3 py-1 rounded"></h5>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewImage(linkAnh, tenFigure) {
            document.getElementById('modalImageSrc').src = linkAnh;
            document.getElementById('modalImageTitle').innerText = tenFigure;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
    </script>
</body>
</html>