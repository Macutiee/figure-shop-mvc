<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ Sơ Của Tôi - Ma's Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #fff5f7; font-family: 'Segoe UI', sans-serif; }
        .profile-box { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(216, 27, 96, 0.05); padding: 30px; }
        .nav-pills .nav-link { color: #d81b60; font-weight: bold; border-radius: 12px; margin-bottom: 10px; padding: 12px 20px; transition: 0.3s;}
        .nav-pills .nav-link:hover { background: #ffe0e6; }
        .nav-pills .nav-link.active { background: #ff4d6d; color: white; box-shadow: 0 4px 10px rgba(255, 77, 109, 0.3); }
        .table th { border-bottom: 2px solid #ffc2d1; color: #d81b60; padding: 15px; }
        .table td { vertical-align: middle; padding: 15px; border-bottom: 1px solid #ffe0e6; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color: #d81b60;"><i class="fa-solid fa-face-smile-wink me-2"></i>Tài Khoản Của Tôi</h3>
        <a href="index.php" class="btn btn-outline-danger rounded-pill px-4"><i class="fa-solid fa-arrow-left me-2"></i>Về Trang Chủ</a>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="profile-box text-center mb-4">
                <?php
                    $avatar_url = (!empty($userInfo['avatar']) && file_exists($userInfo['avatar']))
                        ? BASE_URL . '/' . $userInfo['avatar']
                        : "https://ui-avatars.com/api/?name=" . urlencode($userInfo['fullname']) . "&background=fecfef&color=d81b60&size=100&bold=true";
                ?>
                <img src="<?= $avatar_url ?>" class="rounded-circle shadow-sm mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                <h5 class="fw-bold text-danger"><?= htmlspecialchars($userInfo['fullname']) ?></h5>
            </div>
            <div class="profile-box p-2">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="info-tab" data-bs-toggle="pill" data-bs-target="#info" type="button" role="tab"><i class="fa-solid fa-user-pen me-2"></i>Hồ Sơ</button>
                    <button class="nav-link" id="orders-tab" data-bs-toggle="pill" data-bs-target="#orders" type="button" role="tab"><i class="fa-solid fa-box-open me-2"></i>Đơn Hàng Đã Mua</button>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="profile-box h-100">
                <div class="tab-content" id="v-pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <h5 class="fw-bold mb-4" style="color: #ff4d6d;">Cập Nhật Thông Tin</h5>
                        <form action="index.php?action=update_profile" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="fw-bold text-muted mb-2">Ảnh đại diện</label>
                                <input type="file" name="avatar" class="form-control p-2" style="border-radius: 10px;">
                                <small class="form-text text-muted">Chọn ảnh mới để thay đổi. Bỏ trống nếu không muốn đổi.</small>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted mb-2">Tên hiển thị</label>
                                <input type="text" name="fullname" class="form-control p-2" style="border-radius: 10px;" value="<?= htmlspecialchars($userInfo['fullname']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted mb-2">Email (Không thể đổi)</label>
                                <input type="email" class="form-control p-2" style="border-radius: 10px; background: #f8f9fa;" value="<?= htmlspecialchars($userInfo['email']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted mb-2">Số điện thoại</label>
                                <input type="text" class="form-control p-2" style="border-radius: 10px; background: #f8f9fa;" value="<?= htmlspecialchars($userInfo['phone']) ?>" readonly>
                            </div>
                            <hr class="my-4 text-muted">
                            <h6 class="fw-bold text-danger mb-3"><i class="fa-solid fa-key me-2"></i>Đổi Mật Khẩu (Bỏ trống nếu không đổi)</h6>
                            <div class="mb-3">
                                <input type="password" name="current_password" class="form-control p-2" style="border-radius: 10px;" placeholder="Nhập mật khẩu HIỆN TẠI...">
                            </div>
                            <div class="mb-3">
                                <input type="password" name="new_password" class="form-control p-2" style="border-radius: 10px;" placeholder="Nhập mật khẩu MỚI...">
                            </div>
                            <div class="mb-3">
                                <input type="password" name="confirm_password" class="form-control p-2" style="border-radius: 10px;" placeholder="XÁC NHẬN mật khẩu mới...">
                            </div>

                            <button type="submit" class="btn text-white fw-bold px-5 py-2 mt-2" style="background: linear-gradient(135deg, #ff758f, #ff4d6d); border-radius: 12px;">LƯU THAY ĐỔI</button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="orders" role="tabpanel">
                        <h5 class="fw-bold mb-4" style="color: #ff4d6d;">Lịch Sử Mua Hàng</h5>
                        <?php if (empty($orders)): ?>
                            <div class="text-center py-5">
                                <i class="fa-solid fa-box-open mb-3" style="font-size: 3rem; color: #ffb3c6;"></i>
                                <h6 class="text-muted">Bạn chưa rước bé Waifu nào cả!</h6>
                                <a href="index.php" class="btn btn-sm mt-2 text-white" style="background: #ff4d6d; border-radius: 20px;">Sắm ngay</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table text-center align-middle table-hover">
                                    <thead>
                                        <tr>
                                            <th>Mã Đơn</th>
                                            <th>Ngày Đặt</th>
                                            <th>Địa Chỉ Nhận</th>
                                            <th>Tổng Tiền</th>
                                            <th>Trạng Thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $o): ?>
                                        <tr>
                                            <td class="fw-bold text-danger">#<?= $o['id'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($o['created_at'])) ?></td>
                                            <td class="text-start"><small><?= htmlspecialchars($o['customer_address'] ?? 'N/A') ?></small></td>
                                            <td class="fw-bold text-primary"><?= number_format($o['total_price'] ?? 0) ?>đ</td>
                                            <td>
                                                <?php 
                                                    $st = $o['status'] ?? 'pending';
                                                    if($st == 'pending') echo '<span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Chờ xử lý</span>';
                                                    elseif($st == 'completed') echo '<span class="badge bg-success px-3 py-2 rounded-pill">Đã giao</span>';
                                                    else echo '<span class="badge bg-danger px-3 py-2 rounded-pill">Đã hủy</span>';
                                                ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Tự động mở Tab Đơn hàng nếu trên link có chữ #orders
    document.addEventListener("DOMContentLoaded", function() {
        if(window.location.hash === '#orders') {
            var ordersTab = new bootstrap.Tab(document.getElementById('orders-tab'));
            ordersTab.show();
        }
    });
</script>
</body>
</html>