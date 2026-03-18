<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên Mật Khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: #fff5f7; height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="card p-4 shadow-sm" style="width: 400px; border-radius: 20px; border: 2px solid #ffc2d1;">
        <h4 class="text-center fw-bold" style="color: #d81b60;">Quên Mật Khẩu? 🥺</h4>
        <p class="text-center text-muted small mb-4">Nhập email đăng ký, shop sẽ gửi mã OTP cho bạn!</p>
        <form action="index.php?action=send_otp" method="POST">
            <input type="email" name="email" class="form-control mb-3" placeholder="Nhập Email của bạn..." required style="border-radius: 10px;">
            <button type="submit" class="btn w-100 fw-bold text-white" style="background: #ff4d6d; border-radius: 10px;">Gửi Mã OTP Ngay 🚀</button>
        </form>
    </div>
</body>
</html>