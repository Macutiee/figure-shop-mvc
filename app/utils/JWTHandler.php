<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JWTHandler {
    private $secret_key;

    public function __construct() {
        // Mật khẩu bí mật để khóa Token. Sếp giữ kỹ nha!
        $this->secret_key = "WAIFU_SECRET_KEY_CUA_SEP_LOAN"; [cite: 6942]
    }

    // Hàm tạo Token khi khách đăng nhập thành công
    public function encode($data) {
        $issuedAt = time(); [cite: 6943]
        $expirationTime = $issuedAt + 3600; // Token sống được 1 tiếng [cite: 6944]
        
        $payload = array(
            'iat' => $issuedAt, [cite: 6945]
            'exp' => $expirationTime, [cite: 6945]
            'data' => $data [cite: 6945]
        );
        return JWT::encode($payload, $this->secret_key, 'HS256'); [cite: 6946]
    }

    // Hàm giải mã Token để kiểm tra xem khách có quyền không
    public function decode($jwt) {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256')); [cite: 6947]
            return (array) $decoded->data; [cite: 6947]
        } catch (Exception $e) {
            return null; // Token sai hoặc hết hạn [cite: 6947, 6948]
        }
    }
}
?>