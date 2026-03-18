<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Tài Khoản - Ma's Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #fff5f7; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: 'Segoe UI', sans-serif;}
        .edit-box { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(216, 27, 96, 0.1); padding: 40px; width: 100%; max-width: 500px; }
        .form-control, .form-select { border: 2px solid #ffe0e6; border-radius: 10px; padding: 10px 15px; }
        .form-control:focus, .form-select:focus { border-color: #ff758f; box-shadow: 0 0 10px rgba(255, 117, 143, 0.2); }
        .btn-pink { background: #ff4d6d; color: white; border-radius: 10px; font-weight: bold; transition: 0.3s; }
        .btn-pink:hover { background: #c2185b; color: white; transform: translateY(-2px); }
    </style>
</head>
<body>

    <div class="edit-box">
        <h4 class="text-center fw-bold mb-4" style="color: #d81b60;"><i class="fa-solid fa-user-pen me-2"></i>Chỉnh Sửa Hồ Sơ</h4>
        
        <form action="index.php?action=update_user&id=<?= $user['id'] ?>" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold text-danger">Tên Khách Hàng</label>
                <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user['fullname']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold text-danger">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-danger">Số Điện Thoại</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-danger">Chức Vụ (Phân Quyền)</label>
                <select name="role" class="form-select">
                    <option value="customer" <?= $user['role'] == 'customer' ? 'selected' : '' ?>>Khách Hàng</option>
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin Quản Trị</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="index.php?action=dashboard" class="btn btn-light" style="border: 2px solid #ffe0e6; color: #c2185b; font-weight: bold;">Quay Lại</a>
                <button type="submit" class="btn btn-pink px-4">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>

</body>
</html>