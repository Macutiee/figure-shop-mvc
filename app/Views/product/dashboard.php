<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Ma's Figure Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* --- ĐE-CO TONE HỒNG SANG TRỌNG (THEO TARGET) --- */
        :root {
            --pink-bg: #fff5f7; /* Nền hồng siêu nhạt */
            --pink-sidebar: #ffe0e6; /* Nền sidebar hồng phấn */
            --pink-main: #ff4d6d; /* Hồng đậm (active) */
            --pink-text: #b01c4e; /* Chữ hồng tro đậm */
            --pink-badge: #e63e6d; /* Màu huy hiệu */
        }

        body { background-color: var(--pink-bg); font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; color: var(--pink-text); }
        
        /* Hiệu ứng bo tròn và đổ bóng cho mọi thứ */
        .rounded-box { background: #fff; border-radius: 20px; box-shadow: 0 10px 25px rgba(216, 27, 96, 0.08); border: none; padding: 25px; }

        /* Sidebar tối giản */
        .sidebar { background-color: var(--pink-sidebar); min-height: 100vh; border-right: none; padding: 25px 15px; }
        .sidebar-brand { color: #d81b60; font-weight: 900; font-size: 1.6rem; text-align: center; margin-bottom: 35px; letter-spacing: 0.5px;}
        .nav-link { color: #c2185b; font-weight: 600; margin-bottom: 12px; border-radius: 12px; transition: 0.3s; padding: 12px 18px; }
        .nav-link:hover { background-color: rgba(255, 77, 109, 0.1); color: var(--pink-main); }
        .nav-link.active { background-color: var(--pink-main); color: white; box-shadow: 0 4px 10px rgba(255, 77, 109, 0.3); }

        /* Header bọc tròn */
        .admin-header { background: #fff; border-radius: 15px; padding: 15px 25px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(216, 27, 96, 0.05); }

        /* Mấy cái thẻ nhỏ (Stat Cards) độ lại cực sang */
        .stat-card { background: linear-gradient(135deg, #fff 0%, #fff0f3 100%); border-radius: 15px; border: none; transition: 0.3s; padding: 20px; box-shadow: 0 5px 15px rgba(216, 27, 96, 0.05); }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(216, 27, 96, 0.1); }
        .stat-icon { font-size: 1.5rem; color: #ff758f; background: #fff; border-radius: 12px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(216, 27, 96, 0.05); }
        .stat-title { font-size: 0.95rem; font-weight: 600; color: #c2185b; margin-top: 15px;}
        .stat-value { font-size: 1.7rem; font-weight: 900; color: #d81b60; }
        .stat-percent { font-size: 0.8rem; border-radius: 20px; padding: 2px 10px; }

        /* Khung chứa biểu đồ */
        .chart-box { background: #fff; border-radius: 20px; box-shadow: 0 10px 25px rgba(216, 27, 96, 0.08); padding: 25px; margin-bottom: 30px; }
        .chart-box h5 { color: #c2185b; font-weight: 700; border-bottom: 2px dashed #ffc2d1; padding-bottom: 15px; margin-bottom: 20px;}

        /* Bảng danh sách tối giản */
        .table-box { background: #fff; border-radius: 20px; box-shadow: 0 10px 25px rgba(216, 27, 96, 0.08); padding: 25px; }
        .table th { color: #c2185b; font-weight: 700; border-bottom: 2px solid #ffc2d1; padding: 15px; }
        .table td { padding: 15px; border-bottom: 1px solid #ffe4e8; }
        .table-striped>tbody>tr:nth-of-type(odd)>* { background-color: #fff9fa; }
        
        .badge-pink { background-color: var(--pink-badge); color: white; border-radius: 20px; padding: 5px 15px;}
        .btn-pink-sm { background-color: #fff; color: #e63e6d; border: 1px solid #ff758f; border-radius: 20px; padding: 4px 12px; font-size: 0.8rem; transition: 0.3s;}
        .btn-pink-sm:hover { background-color: #ff758f; color: white;}
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-md-2 sidebar d-none d-md-block">
                <div class="sidebar-brand"><i class="fa-solid fa-rose"></i> MA'S STORE</div>
                <ul class="nav flex-column px-2">
                    <li class="nav-item"><a class="nav-link active" href="#"><i class="fa-solid fa-chart-pie me-2"></i> Tổng Quan</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=admin"><i class="fa-solid fa-box-open me-2"></i> QL Sản Phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=manage_orders"><i class="fa-solid fa-file-invoice-dollar me-2"></i> Đơn Hàng</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-store me-2"></i> Xem Cửa Hàng</a></li>
                </ul>
            </div>

            <div class="col-md-10 p-4">
                <div class="admin-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: #d81b60;">Chào Loan! ✨</h4>
                        <p class="text-muted mb-0 small">Chào mừng tới với Dashboard dành cho Admin</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="search-box position-relative">
                        <input type="text" class="form-control rounded-pill pe-5" placeholder="Tìm đơn hàng, khách hàng..." style="background: #fff0f3; border-color: #ffc2d1; width: 280px;">
                            <i class="fa-solid fa-search position-absolute text-danger" style="top: 13px; right: 20px;"></i>
                        </div>
                        <!-- KHÚC AVATAR ADMIN (Menu xổ xuống) -->
                        <div class="dropdown">
                            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false" style="background: white; padding: 5px 15px 5px 5px; border-radius: 50px; border: 1px solid #ffe0e6; box-shadow: 0 2px 5px rgba(216, 27, 96, 0.1);">
                                <img src="https://ui-avatars.com/api/?name=<?= isset($_SESSION['user']) ? urlencode($_SESSION['user']['fullname']) : 'Admin' ?>&background=ff4d6d&color=fff" alt="admin" width="35" height="35" class="rounded-circle shadow-sm">
                                <span class="fw-bold" style="color: #d81b60;"><?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['fullname']) : 'Sếp Loan' ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end text-small shadow-lg" style="border-radius: 12px; border: none; background-color: #fff9fa; margin-top: 10px;">
                                <li><a class="dropdown-item py-2 fw-bold" href="index.php"><i class="fa-solid fa-store me-2" style="color: #ff4d6d;"></i>Ra Trang Chủ Cửa Hàng</a></li>
                                <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#adminProfileModal"><i class="fa-solid fa-user-gear me-2" style="color: #ff4d6d;"></i>Hồ sơ cá nhân</a></li>
                                <li><hr class="dropdown-divider" style="border-color: #ffe0e6;"></li>
                                <li><a class="dropdown-item py-2 text-danger fw-bold" href="index.php?action=logout"><i class="fa-solid fa-right-from-bracket me-2 text-danger"></i>Đăng xuất ngay</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-5">
                    <div class="col-md-3">
                        <div class="stat-card h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                                <span class="badge badge-pink stat-percent">+5% <i class="fa-solid fa-arrow-up"></i></span>
                            </div>
                            <h6 class="stat-title">Khách Hàng Mới</h6>
                            <div class="stat-value"><?= count($users) ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="stat-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                                <span class="badge bg-success rounded-pill px-3 stat-percent text-white">+14% <i class="fa-solid fa-arrow-up"></i></span>
                            </div>
                            <h6 class="stat-title">Doanh Thu Hôm Nay</h6>
                            <div class="stat-value text-danger"><?= number_format($todayRevenue) ?> đ</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="stat-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                            </div>
                            <h6 class="stat-title">Đơn Hàng Hôm Nay</h6>
                            <div class="stat-value text-success"><?= $newOrdersToday ?> <span class="fs-6 fw-normal">đơn</span></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="stat-icon"><i class="fa-solid fa-wallet"></i></div>
                            </div>
                            <h6 class="stat-title">Tổng Doanh Thu</h6>
                            <div class="stat-value text-danger" style="font-size: 1.5rem;"><?= number_format($totalRevenue) ?> đ</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5 g-4">
                    <div class="col-md-7">
                        <div class="chart-box h-100 mb-0">
                            <h5><i class="fa-solid fa-chart-line me-2"></i> Xu Hướng Doanh Thu Tháng 3</h5>
                            <div style="position: relative; height: 300px; width: 100%;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="chart-box h-100 mb-0">
                            <h5><i class="fa-solid fa-tags me-2"></i> Phân Khúc Doanh Số Theo Hãng</h5>
                            <div style="position: relative; height: 300px; width: 100%;">
                                <canvas id="brandPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-box mb-4 p-3 d-flex align-items-center justify-content-between" style="background: #fff0f3; border: 2px dashed #ffc2d1;">
                    <h6 class="fw-bold mb-0 text-danger"><i class="fa-solid fa-bullseye me-2"></i>Mục Tiêu Doanh Thu Tháng 3:</h6>
                    <div class="progress flex-grow-1 mx-3" style="height: 20px; border-radius: 15px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: <?= $revenueTargetPercent ?>%;" aria-valuenow="<?= $revenueTargetPercent ?>" aria-valuemin="0" aria-valuemax="100"><?= $revenueTargetPercent ?>% Đã đạt</div>
                    </div>
                    <span class="fw-bold" style="color: #b01c4e;">Cố lên! 💪</span>
                </div>
                <div class="table-box">
                    <h5 class="fw-bold mb-4" style="color: #ff4d6d;"><i class="fa-solid fa-users-gear me-2"></i> Quản Lý Tài Khoản Khách Hàng</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-borderless align-middle text-center">
                            <thead class="text-danger">
                                <tr>
                                    <th>ID</th>
                                    <th class="text-start">Tên Khách Hàng</th>
                                    <th>Email</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Phân Quyền</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $user): ?>
                                <tr>
                                    <td class="fw-bold text-danger">#<?= $user['id'] ?></td>
                                    <td class="text-start fw-bold">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['fullname']) ?>&background=fff0f3&color=d81b60&bold=true" class="rounded-circle me-2" width="35">
                                        <?= htmlspecialchars($user['fullname']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['phone']) ?></td>
                                    <td>
                                        <?php if($user['role'] == 'admin'): ?>
                                            <span class="badge bg-danger rounded-pill px-3">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-success rounded-pill px-3">Khách hàng</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Nút Xem (Gọi Modal) -->
                                        <button class="btn btn-pink-sm" data-bs-toggle="modal" data-bs-target="#viewModal<?= $user['id'] ?>"><i class="fa-solid fa-eye"></i></button>
                                        
                                        <!-- Nút Sửa (Chuyển trang edit) -->
                                        <a href="index.php?action=edit_user&id=<?= $user['id'] ?>" class="btn btn-pink-sm ms-1"><i class="fa-solid fa-pen-to-square"></i></a>

                                        <!-- CÁI THẺ ẨN ĐỂ HIỆN HỒ SƠ KHI BẤM NÚT XEM -->
                                        <div class="modal fade" id="viewModal<?= $user['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" style="border-radius: 20px; border: none; background: #fff5f7;">
                                                    <div class="modal-header" style="background: #ff758f; color: white; border-radius: 20px 20px 0 0;">
                                                        <h5 class="modal-title"><i class="fa-solid fa-id-card me-2"></i>Hồ Sơ Khách Hàng</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center p-4">
                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['fullname']) ?>&background=fecfef&color=d81b60&bold=true&size=100" class="rounded-circle mb-3 shadow">
                                                        <h4 style="color: #d81b60; font-weight: bold;"><?= htmlspecialchars($user['fullname']) ?></h4>
                                                        <p class="text-muted mb-1"><i class="fa-solid fa-envelope me-2 text-danger"></i><?= htmlspecialchars($user['email']) ?></p>
                                                        <p class="text-muted mb-3"><i class="fa-solid fa-phone me-2 text-danger"></i><?= htmlspecialchars($user['phone']) ?></p>
                                                        <span class="badge <?= $user['role'] == 'admin' ? 'bg-danger' : 'bg-success' ?> rounded-pill px-4 py-2 fs-6">
                                                            <?= $user['role'] == 'admin' ? 'Quản Trị 😎' : 'Khách Hàng' ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- MODAL HỒ SƠ CÁ NHÂN & ĐỔI MẬT KHẨU -->
    <div class="modal fade" id="adminProfileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none; background: #fff5f7;">
                <div class="modal-header" style="background: #ff758f; color: white; border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title"><i class="fa-solid fa-user-shield me-2"></i>Hồ Sơ Của Sếp</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="index.php?action=update_profile" method="POST">
                        <div class="mb-3">
                            <label class="fw-bold" style="color: #d81b60;">Tên hiển thị</label>
                            <input type="text" name="fullname" class="form-control" style="border-radius: 10px;" value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['fullname']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold" style="color: #d81b60;">Email đăng nhập (Không thể đổi)</label>
                            <input type="email" class="form-control text-muted" style="border-radius: 10px; background: #ffe0e6;" value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['email']) : '' ?>" readonly>
                        </div>
                        
                        <hr class="my-4" style="border-color: #ffc2d1;">
                        
                        <h6 class="fw-bold text-danger mb-3"><i class="fa-solid fa-key me-2"></i>Đổi Mật Khẩu</h6>
                        <div class="mb-3">
                            <input type="password" name="new_password" class="form-control" style="border-radius: 10px;" placeholder="Nhập mật khẩu mới...">
                        </div>
                        
                        <button type="submit" class="btn w-100 fw-bold text-white mt-2 py-2" style="background: linear-gradient(135deg, #ff758f, #ff4d6d); border-radius: 12px; box-shadow: 0 4px 10px rgba(255, 77, 109, 0.3);">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Lưu Thay Đổi ✨
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // --- JAVASCRIPT ĐỂ VẼ 2 BIỂU ĐỒ (Giữ nguyên logic cũ) ---
        
        // 1. Biểu đồ đường Doanh thu
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['15/3', '16/3', '17/3', 'Hôm nay (18/3)'],
                datasets: [{
                    label: 'Doanh thu (đ)',
                    data: [1200000, 1950000, 750000, <?= $todayRevenue ?>],
                    borderColor: '#ff4d6d',
                    backgroundColor: 'rgba(255, 77, 109, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#ff4d6d',
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#b01c4e' }, grid: { color: 'rgba(255, 194, 209, 0.2)' } },
                    x: { ticks: { color: '#b01c4e' }, grid: { color: 'rgba(255, 194, 209, 0.2)' } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // 🚀🚀🚀 2. PHẦN MỚI: ĐÃ ĐỔ MÀU PASTEL KHÁC 🚀🚀🚀
        const brandCtx = document.getElementById('brandPieChart').getContext('2d');

        // Nhúng dữ liệu thật từ PHP vô đây
        const brandLabels = <?= json_encode($brandLabels) ?>;
        const brandSales = <?= json_encode($brandSales) ?>;

        // 👇👇👇 ĐÂY LÀ BẢNG MÀU PASTEL ĐA DẠNG MỚI NHA SẾP 👇👇👇
        const pastelColors = [
            '#93C5FD', // Pastel Blue (Xanh biển dịu nhẹ)
            '#34D399', // Pastel Green (Xanh lá bạc hà mát mắt)
            '#FBBF24', // Pastel Yellow (Vàng kem ngọt ngào)
            '#A78BFA', // Pastel Purple (Tím oải hương lãng mạn)
            '#F472B6'  // Pastel Pink (Hồng nhẹ - giữ lại 1 cái soft cho tiệp theme)
        ];
        // 👆👆👆👆👆👆👆👆👆👆👆👆👆👆👆👆👆👆👆👆👆

        const brandPieChart = new Chart(brandCtx, {
            type: 'pie',
            data: {
                labels: brandLabels,
                datasets: [{
                    data: brandSales,
                    backgroundColor: pastelColors, // <--- Gắn bảng màu mới vô đây nha
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right', // Đặt chú thích bên phải cho sang
                        labels: {
                            color: '#b01c4e', // Màu chữ chú thích
                            font: { size: 13, weight: '600' },
                            padding: 15
                        }
                    }
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>