<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: #fff5f7; height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="card p-4 shadow-sm" style="width: 400px; border-radius: 20px; border: 2px solid #ffc2d1;">
        <h4 class="text-center fw-bold text-success">Đã gửi mã OTP! 📬</h4>
        <p class="text-center text-muted small mb-4">Check mail <b><?= $_SESSION['reset_email'] ?? '' ?></b> nha!</p>
        
        <!-- Chỗ này Sếp mốt viết thêm hàm process_reset_password vô Controller nha, nay làm demo OTP trước -->
        <form action="index.php?action=reset_password" method="POST"> 
            <input type="text" name="otp_code" class="form-control mb-3 text-center fs-4 fw-bold text-danger" placeholder="Nhập 6 số OTP" required maxlength="6" style="letter-spacing: 5px; border-radius: 10px;">
            <input type="password" name="new_password" class="form-control mb-3" placeholder="Nhập Mật khẩu mới" required style="border-radius: 10px;">
            <button type="submit" class="btn w-100 fw-bold text-white" style="background: #ff4d6d; border-radius: 10px;">Đổi Mật Khẩu ✨</button>
        </form>
    </div>
</body>
</html>