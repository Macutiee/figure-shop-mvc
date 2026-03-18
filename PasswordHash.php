<?php
require_once 'app/Core/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Mật khẩu gốc mình muốn đặt cho tất cả tài khoản
$passGoc = '123456';

// Băm nó ra bã bằng thuật toán xịn nhất
$passBam = password_hash($passGoc, PASSWORD_DEFAULT);

// Ra lệnh cập nhật lại toàn bộ mật khẩu trong DB
$stmt = $conn->prepare("UPDATE users SET password = :passBam");
$stmt->execute(['passBam' => $passBam]);

echo "<div style='background: #fff0f3; padding: 50px; text-align: center; font-family: sans-serif; color: #d81b60;'>";
echo "<h1>🎉 Băm mật khẩu thành công!</h1>";
echo "<h3>Mật khẩu '123456' của bà giờ đã biến thành cái nùi này:</h3>";
echo "<p style='font-size: 1.2rem; background: #ffc2d1; padding: 15px; border-radius: 10px; display: inline-block;'><b>" . $passBam . "</b></p>";
echo "<p>Thách cha hacker nào dịch ngược được luôn! Về trang đăng nhập test thử đi bà!</p>";
echo "</div>";
?>