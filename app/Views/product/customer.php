<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Ma's Store - Mô hình chính hãng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* --- SỬA LỖI ẢNH KHÔNG ĐỀU --- */
        .product-card img.product-img {
        height: 300px; /* Bà có thể chỉnh con số này (ví dụ 250px hoặc 350px) sao cho vừa mắt */
        object-fit: cover; /* QUAN TRỌNG: Nó sẽ cắt ảnh một chút để vừa khung mà KHÔNG làm méo hình */
        width: 100%; /* Đảm bảo ảnh đầy chiều ngang */
}
        /* --- 2. HIỆU ỨNG NAVBAR SIÊU MƯỢT (PASTEL PINK KUTE) --- */
        .shop-navbar {
            background-color: #ffe4e8; /* Nền hồng pastel siêu ngọt */
            box-shadow: 0 4px 15px rgba(255, 182, 193, 0.4); /* Bóng đổ cũng ánh hồng luôn */
            padding: 15px 0;
            border-bottom: 2px solid #ffc2d1; /* Viền dưới hồng đậm hơn xíu tạo điểm nhấn */
        }
        
        /* Logo đổi sang tone Đỏ hồng mâm xôi */
        .brand-logo {
            font-size: 1.8rem;
            font-weight: 900;
            background: linear-gradient(to right, #ff0844, #ffb199); /* Đỏ hồng sang cam đào */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.5px;
            text-decoration: none;
        }

        /* Khung tìm kiếm phong cách kẹo ngọt */
        .search-box {
            border: 2px solid #ffb3c6; /* Viền hồng nhạt */
            border-radius: 50px;
            background-color: #fff; 
            overflow: hidden;
            display: flex;
            transition: all 0.3s ease;
        }
        .search-box:focus-within {
            border-color: #ff758f; /* Click vào đậm viền lên */
            box-shadow: 0 0 8px rgba(255, 117, 143, 0.3);
        }
        .search-box input {
            border: none;
            box-shadow: none !important;
            padding: 10px 20px;
            width: 100%;
            background-color: transparent;
            color: #555; 
        }
        .search-box input::placeholder {
            color: #ffb3c6; /* Chữ placeholder màu hồng mờ */
        }
        .search-box input:focus { outline: none; }
        .search-box button {
            background-color: #ff758f; /* Nút tìm kiếm hồng đậm */
            color: #fff;
            border: none;
            padding: 0 30px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .search-box button:hover { background-color: #ff4d6d; }

        /* Nút Icon Tròn (Giỏ hàng, Admin) */
        .icon-btn {
            width: 48px;
            height: 48px;
            background-color: #ffffff; /* Nền trắng nổi trên nền hồng */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #ff4d6d; /* Icon màu hồng đậm */
            font-size: 1.3rem;
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid #ffc2d1;
            box-shadow: 0 2px 8px rgba(255, 182, 193, 0.4);
        }
        .icon-btn:hover {
            background-color: #ff758f; /* Rê chuột vào đổi nền hồng */
            transform: translateY(-4px); 
            color: #ffffff; /* Icon trắng */
            border-color: #ff758f;
        }
        
        /* Cục đỏ báo số lượng giỏ hàng */
        .cart-dot {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: #ff0844; /* Đỏ dâu nổi bần bật */
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
            padding: 3px 7px;
            border-radius: 50px;
            border: 2px solid #ffe4e8; /* Viền trùng màu nền Navbar cho tiệp màu */
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        /* 1. Fix chữ Tìm kiếm bị rớt dòng */
        .search-box button {
            white-space: nowrap; /* Lệnh cấm rớt dòng thần thánh */
            padding: 0 25px; /* Ép bớt lề lại xíu cho đỡ chật */
        }

        /* 2. Banner Tone Hồng Kẹo Ngọt */
        .pink-banner {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
            border: 2px solid #ffc2d1;
        }
        .pink-banner h1, .pink-banner p {
            color: #d81b60 !important; /* Chữ hồng đậm cho dễ đọc */
            text-shadow: 2px 2px 4px rgba(255,255,255,0.7); /* Viền sáng phát quang */
        }

        /* 3. Nút Mua Ngay Cute */
        .btn-buy-cute {
            background: linear-gradient(to right, #ff758f, #ff4d6d);
            border: none;
            color: white;
            box-shadow: 0 4px 10px rgba(255, 77, 109, 0.3);
            transition: all 0.3s ease;
        }
        .btn-buy-cute:hover {
            transform: translateY(-3px); /* Rê chuột vào nảy lên nhẹ */
            box-shadow: 0 6px 15px rgba(255, 77, 109, 0.5);
            color: white;
        }

        /* 4. Đổi luôn màu cái tem "Còn hàng" xanh lá cho đỡ vô duyên */
        .badge-stock-cute {
            background-color: #ffb3c6 !important;
            color: #fff !important;
        }
        /* --- 3. HIỆU ỨNG FLASH SALE CỰC CHÁY --- */
        .flash-sale-bg {
            background-color: #ff6b81; /* Nền đỏ hồng rực rỡ */
            border-radius: 12px;
            padding: 20px;
        }
        .countdown-box {
            background: #fff;
            color: #ff4757;
            font-weight: 900;
            font-size: 1.2rem;
            padding: 5px 12px;
            border-radius: 8px;
            margin: 0 4px;
            display: inline-block;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .flash-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            transition: transform 0.3s;
        }
        .flash-card:hover { transform: translateY(-5px); }
        
        /* Tag giảm giá góc phải */
        .discount-badge {
            position: absolute;
            top: 0; right: 0;
            background: #ff4757; color: white;
            font-weight: bold; font-size: 0.9rem;
            padding: 4px 10px;
            border-radius: 0 8px 0 8px;
            z-index: 10;
        }
        
        /* Ruy băng In Stock góc trái */
        .instock-ribbon {
            position: absolute;
            top: 15px; left: -30px;
            background: #1e90ff; color: white;
            font-size: 0.75rem; font-weight: bold;
            padding: 4px 35px;
            transform: rotate(-45deg);
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .old-price {
            text-decoration: line-through;
            color: #a4b0be; font-size: 0.85rem;
        }
        
        /* Thanh tiến độ bốc cháy */
        .progress-sale {
            height: 16px;
            background-color: #ffcccb;
            border-radius: 10px;
            margin-top: 10px;
            position: relative;
        }
        .progress-sale-bar {
            background: linear-gradient(to right, #ff9a9e, #ff4757);
            height: 100%; border-radius: 10px;
        }
        .progress-text {
            position: absolute; width: 100%; text-align: center;
            top: -2px; font-size: 0.75rem; color: white; font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        /* --- FIX LỖI THẺ SẢN PHẨM & KHOẢNG TRẮNG --- */
        .product-card { border: none; border-radius: 12px; transition: 0.3s; overflow: hidden; background: #fff; }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
        
        /* Khóa cứng chiều cao ảnh, tự cắt cúp không bị méo */
        .product-img { height: 280px; object-fit: cover; width: 100%; cursor: pointer; transition: 0.5s; }
        .product-card:hover .product-img { transform: scale(1.05); }
        
        /* Ép tem Hãng bay lên góc, KHÔNG được đẩy ảnh xuống */
        .badge-brand { position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7) !important; color: white !important; font-size: 0.8rem; z-index: 10; }
        
        /* Cố định chiều cao Tiêu đề để các thẻ luôn bằng nhau */
        .figure-title { font-size: 1.1rem; font-weight: 600; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 3rem; }
        .price-text { color: #ff424e; font-weight: 700; font-size: 1.3rem; }
    </style>
</head>
<body>

    <nav class="navbar shop-navbar sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            
            <a class="brand-logo" href="index.php">
                ✨ Ma's Store
            </a>
            
            <form action="index.php" method="GET" class="mx-auto" style="width: 50%; max-width: 600px;">
                <div class="search-box shadow-sm">
                    <span class="d-flex align-items-center ps-3 text-muted">🔍</span>
                    <input type="text" name="search" 
                           placeholder="Bạn muốn rước Waifu nào về nhà?..." 
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit">Tìm kiếm</button>
                </div>
                <?php if(isset($_GET['search']) && $_GET['search'] != ''): ?>
                    <div class="text-center mt-1">
                        <a href="index.php" class="text-danger text-decoration-none" style="font-size: 0.85rem;">✖ Hủy tìm kiếm</a>
                    </div>
                <?php endif; ?>
            </form>

            <div class="d-flex align-items-center gap-3">
    
    <!-- Nút Giỏ Hàng -->
    <a href="index.php?action=cart" class="position-relative text-dark text-decoration-none">
        <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
            <i class="fa-solid fa-cart-shopping fs-5" style="color: #ff4d6d;"></i>
        </div>
        
        <!-- CỤC CHỚP ĐỎ HIỆN SỐ ĐÃ ĐƯỢC CẮM ĐIỆN -->
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0 ?>
        </span>
    </a>

    <!-- KHU VỰC TÀI KHOẢN (ĐỘNG) -->
    <?php if(isset($_SESSION['user'])): ?>
        <!-- 1. NẾU ĐÃ ĐĂNG NHẬP -> HIỆN AVATAR VÀ MENU -->
        <div class="dropdown">
            <a class="text-decoration-none fw-bold dropdown-toggle d-flex align-items-center gap-2 bg-white rounded-pill shadow-sm p-1" href="#" role="button" data-bs-toggle="dropdown" style="color: #d81b60; border: 1px solid #ffe0e6;">
                <?php
                    $header_avatar_url = (isset($_SESSION['user']['avatar']) && !empty($_SESSION['user']['avatar']) && file_exists($_SESSION['user']['avatar']))
                        ? BASE_URL . '/' . $_SESSION['user']['avatar']
                        : "https://ui-avatars.com/api/?name=" . urlencode($_SESSION['user']['fullname']) . "&background=fecfef&color=d81b60&bold=true";
                ?>
                <img src="<?= $header_avatar_url ?>" class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                <span class="d-none d-md-block"><?= htmlspecialchars($_SESSION['user']['fullname']) ?></span>
            </a>
            
            <ul class="dropdown-menu dropdown-menu-end shadow" style="border: none; border-radius: 15px; background-color: #fff9fa;">
                <!-- Nếu là Admin thì hiện thêm nút vô Dashboard -->
                <?php if($_SESSION['user']['role'] === 'admin'): ?>
                    <li><a class="dropdown-item fw-bold text-danger py-2" href="index.php?action=dashboard"><i class="fa-solid fa-chart-line me-2"></i>Vào Dashboard</a></li>
                    <li><hr class="dropdown-divider"></li>
                <?php endif; ?>
                
                <li><a class="dropdown-item py-2 fw-bold" href="index.php?action=profile" style="color: #d81b60;"><i class="fa-solid fa-user me-2"></i>Hồ sơ của tôi</a></li>
<li><a class="dropdown-item py-2 fw-bold" href="index.php?action=profile#orders" style="color: #d81b60;"><i class="fa-solid fa-box me-2"></i>Đơn hàng đã mua</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item fw-bold text-danger py-2" href="index.php?action=logout"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</a></li>
            </ul>
        </div>

    <?php else: ?>
        <!-- 2. NẾU CHƯA ĐĂNG NHẬP -> HIỆN NÚT LOGIN / REGISTER -->
        <div class="d-none d-md-flex gap-2">
            <a href="index.php?action=login" class="btn fw-bold rounded-pill px-4" style="border: 2px solid #ff4d6d; color: #ff4d6d; background: white;">Đăng Nhập</a>
            <a href="index.php?action=login#register" class="btn fw-bold rounded-pill px-4 text-white shadow-sm" style="background: linear-gradient(135deg, #ff758f, #ff4d6d);">Đăng Ký</a>
        </div>
    <?php endif; ?>

</div>

        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="p-5 text-center pink-banner rounded-4 shadow-sm">
            <h1 class="fw-bold display-5">🌸 Thiên Đường Figure Chính Hãng 🌸</h1>
            <p class="lead fw-bold mt-3">Săn lùng những bé Waifu cực phẩm với giá yêu thương nhất</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="flash-sale-bg shadow-lg">
            
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-light pb-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <h2 class="text-white fw-bold mb-0" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">⚡ FLASH SALE</h2>
                    <div class="d-flex align-items-center">
                        <span class="countdown-box" id="fs-h">00</span> <span class="text-white fw-bold">:</span>
                        <span class="countdown-box" id="fs-m">00</span> <span class="text-white fw-bold">:</span>
                        <span class="countdown-box" id="fs-s">00</span>
                    </div>
                </div>
                <a href="#" class="btn btn-light fw-bold text-danger rounded-pill px-4 shadow-sm">Xem tất cả »</a>
            </div>

            <div class="row row-cols-2 row-cols-md-4 g-3">
                <?php
                $hasFlashSale = false;
                foreach ($products as $row): 
                    // CHỈ HIỆN NHỮNG MÓN ĐƯỢC ADMIN TICK CHỌN FLASH SALE
                    if (isset($row['is_flash_sale']) && $row['is_flash_sale'] == 1):
                        $hasFlashSale = true;
                        
                        // Tự động tính phần trăm giảm giá
                        $discount = 0;
                        if ($row['old_price'] > $row['price'] && $row['old_price'] > 0) {
                            $discount = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
                        }
                        
                        // Tính phần trăm thanh tiến độ
                        $total_items = $row['stock'] + $row['sold_count'];
                        $percent = $total_items > 0 ? round(($row['sold_count'] / $total_items) * 100) : 0;
                ?>
                <div class="col">
                    <div class="flash-card shadow-sm h-100 pb-2 d-flex flex-column">
                        <div class="instock-ribbon">In Stock</div>
                        <?php if($discount > 0): ?>
                            <div class="discount-badge">-<?= $discount ?>%</div>
                        <?php endif; ?>
                        
                        <div class="overflow-hidden">
                            <img src="<?= $row['image'] ?>" class="w-100 product-img" style="height: 220px; object-fit: contain; padding: 10px; cursor: pointer;" onclick="viewImage('<?= $row['image'] ?>', '<?= htmlspecialchars($row['name']) ?>')">
                        </div>
                        
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <h6 class="figure-title mb-2" style="font-size: 0.95rem; height: 2.8rem;"><?= htmlspecialchars($row['name']) ?></h6>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <span class="text-danger fw-bold fs-5"><?= number_format($row['price']) ?>đ</span>
                                    <?php if(isset($row['old_price']) && $row['old_price'] > 0): ?>
                                        <span class="old-price"><?= number_format($row['old_price']) ?>đ</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="progress-sale mb-3"> <div class="progress-sale-bar" style="width: <?= $percent ?>%"></div>
                                    <span class="progress-text">🔥 Đã bán <?= isset($row['sold_count']) ? $row['sold_count'] : 0 ?></span>
                                </div>

                                <?php if ($row['stock'] > 0): ?>
                                    <div class="d-flex gap-2">
                                        <a href="index.php?action=buy_now&id=<?= $row['id'] ?>" class="btn btn-buy-cute flex-grow-1 fw-bold py-1 rounded-3 text-nowrap" title="Mua ngay và thanh toán" style="font-size: 0.95rem;">
                                            ⚡ Mua ngay
                                        </a>
                                        <a href="index.php?action=add_to_cart&id=<?= $row['id'] ?>" class="btn btn-outline-danger py-1 px-3 rounded-3 d-flex align-items-center justify-content-center" title="Thêm vào giỏ hàng" style="border-color: #ff758f; color: #ff758f;">
                                            <span class="fs-6">🛒</span>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary w-100 fw-bold py-1 rounded-3" disabled style="font-size: 0.95rem;">
                                        ❌ Hết Hàng
                                    </button>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    endif; 
                endforeach; 
                if (!$hasFlashSale):
                ?>
                    <div class="col-12 text-center text-white py-4 fs-5">
                        ⏳ Sự kiện Flash Sale đang được chuẩn bị. Vui lòng quay lại sau!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
            
            <?php foreach ($products as $row): ?>
            <div class="col">
                <div class="card h-100 product-card shadow-sm position-relative">
                    
                    <span class="badge badge-brand rounded-pill"><?= htmlspecialchars($row['brand']) ?></span>
                    
                    <div class="overflow-hidden">
                        <img src="<?= $row['image'] ?>" class="card-img-top product-img" 
                             onclick="viewImage('<?= $row['image'] ?>', '<?= htmlspecialchars($row['name']) ?>')">
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title figure-title" title="<?= htmlspecialchars($row['name']) ?>">
                            <?= htmlspecialchars($row['name']) ?>
                        </h5>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto mb-3">
                            <span class="card-text price-text mb-0"><?= number_format($row['price']) ?> đ</span>
                            
                            <?php if ($row['stock'] > 0): ?>
                                <span class="text-secondary fw-bold" style="font-size: 0.9rem;">SL: <?= $row['stock'] ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Hết hàng</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($row['stock'] > 0): ?>
                            <div class="d-flex gap-2">
                                <a href="index.php?action=buy_now&id=<?= $row['id'] ?>" class="btn btn-buy-cute flex-grow-1 fw-bold py-2 rounded-3 text-nowrap" title="Mua ngay và thanh toán">
                                    Mua ngay
                                </a>
                                
                                <a href="index.php?action=add_to_cart&id=<?= $row['id'] ?>" class="btn btn-outline-danger py-2 px-3 rounded-3 d-flex align-items-center justify-content-center" title="Thêm vào giỏ hàng" style="border-color: #ff758f; color: #ff758f;">
                                    <span class="fs-5">🛒</span>
                                </a>
                            </div>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100 fw-bold py-2 rounded-3" disabled>
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
        // Script chạy đồng hồ Flash Sale
        function startFlashSaleTimer(duration) {
            let timer = duration, hours, minutes, seconds;
            setInterval(function () {
                hours = parseInt(timer / 3600, 10);
                minutes = parseInt((timer % 3600) / 60, 10);
                seconds = parseInt(timer % 60, 10);

                hours = hours < 10 ? "0" + hours : hours;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                document.getElementById('fs-h').textContent = hours;
                document.getElementById('fs-m').textContent = minutes;
                document.getElementById('fs-s').textContent = seconds;

                if (--timer < 0) { timer = duration; } // Hết giờ tự reset chạy lại
            }, 1000);
        }
        
        // Setup đếm ngược 5 tiếng 30 phút (tính bằng giây)
        window.onload = function () {
            startFlashSaleTimer((5 * 3600) + (30 * 60)); 
        };
    </script>
    <script>
    // MA THUẬT CHỐNG GIẬT TRANG LÊN ĐẦU
    document.addEventListener("DOMContentLoaded", function(event) {
        // Khi trang vừa load xong, lấy lại vị trí cũ
        var scrollpos = localStorage.getItem('scrollpos');
        if (scrollpos) window.scrollTo(0, scrollpos);
    });

    window.onbeforeunload = function(e) {
        // Trước khi trang bị tải lại (do bấm thêm giỏ hàng), lưu lẹ vị trí cuộn chuột hiện tại
        localStorage.setItem('scrollpos', window.scrollY);
    };
</script>
</body>
</html>