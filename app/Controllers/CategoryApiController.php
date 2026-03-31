<?php
require_once __DIR__ . '/../utils/JWTHandler.php'; 

class CategoryApiController {
    private $jwtHandler;

    public function __construct() {
        $this->jwtHandler = new JWTHandler(); 
    }

    // Vẫn phải có bảo vệ kiểm tra Token
    private function authenticate() {
        $headers = apache_request_headers(); 
        if (isset($headers['Authorization'])) { 
            $authHeader = $headers['Authorization']; 
            $arr = explode(" ", $authHeader); 
            $jwt = $arr[1] ?? null; 
            
            if ($jwt) {
                return $this->jwtHandler->decode($jwt) ? true : false; 
            }
        }
        return false; 
    }

    // API nhả danh sách các Hãng (Brands)
    public function index() {
        if ($this->authenticate()) { 
            header('Content-Type: application/json'); 
            // Tạm thời mình trả về mảng cố định vì các Hãng này Sếp đang fix cứng, 
            // mốt Sếp có bảng categories trong DB thì gọi Model giống Product nha.
            $categories = [
                ['id' => 1, 'name' => 'Good Smile'],
                ['id' => 2, 'name' => 'SEGA'],
                ['id' => 3, 'name' => 'Banpresto'],
                ['id' => 4, 'name' => 'Bandai'],
                ['id' => 5, 'name' => 'Khác']
            ];
            echo json_encode($categories); 
        } else {
            http_response_code(401); 
            echo json_encode(['message' => 'Lỗi Token: Bạn không có quyền!']); 
        }
    }
}
?>