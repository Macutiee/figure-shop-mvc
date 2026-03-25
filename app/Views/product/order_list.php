<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Đơn Hàng - Ma's Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #fff5f7; font-family: 'Segoe UI', sans-serif; }
        .order-box { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(216, 27, 96, 0.08); padding: 30px; }
        .table th { color: #d81b60; border-bottom: 2px solid #ffc2d1; }
        .status-pending { background: #fff3cd; color: #856404; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; }
        .status-completed { background: #d4edda; color: #155724; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; }
        .status-cancelled { background: #f8d7da; color: #721c24; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; }
        .btn-pink-sm { background: #ff758f; color: white; border-radius: 10px; font-size: 0.8rem; border: none; padding: 5px 10px; }
        .btn-pink-sm:hover { background: #ff4d6d; color: white; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="order-box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0" style="color: #d81b60;"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Danh Sách Đơn Hàng</h3>
            <a href="index.php?action=dashboard" class="btn btn-outline-danger rounded-pill px-4">Quay lại Dashboard</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th class="text-start">Khách Hàng</th>
                        <th>Số Điện Thoại</th>
                        <th>Tổng Tiền</th>
                        <th>Ngày Đặt</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                    <tr>
                        <td class="fw-bold text-danger">#<?= $order['id'] ?></td>
                        <td class="text-start">
                            <div class="fw-bold"><?= htmlspecialchars($order['customer_name'] ?? 'Khách') ?></div>
                            <small class="text-muted"><?= htmlspecialchars($order['customer_address'] ?? '') ?></small>
                        </td>
                        <td><?= htmlspecialchars($order['customer_phone'] ?? '') ?></td>
                        <td class="fw-bold text-primary"><?= number_format((float)($order['total_price'] ?? 0)) ?>đ</td>
                        <td><?= date('d/m/Y', strtotime($order['created_at'] ?? 'now')) ?></td>
                        <td>
                            <?php 
                                $status = $order['status'] ?? 'pending';
                                if($status == 'pending') echo '<span class="status-pending">Chờ xử lý</span>';
                                elseif($status == 'completed') echo '<span class="status-completed">Đã giao</span>';
                                else echo '<span class="status-cancelled">Đã hủy</span>';
                            ?>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-pink-sm dropdown-toggle" data-bs-toggle="dropdown">Sửa</button>
                                <ul class="dropdown-menu shadow border-0">
                                    <li><a class="dropdown-item" href="index.php?action=update_status&id=<?= $order['id'] ?>&status=pending">Chờ xử lý</a></li>
                                    <li><a class="dropdown-item text-success" href="index.php?action=update_status&id=<?= $order['id'] ?>&status=completed">Đã giao</a></li>
                                    <li><a class="dropdown-item text-danger" href="index.php?action=update_status&id=<?= $order['id'] ?>&status=cancelled">Hủy đơn</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>