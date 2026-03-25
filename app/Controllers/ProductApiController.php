<?php
require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../utils/JWTHandler.php'; [cite: 6949]

class ProductApiController {
    private $model;
    private $jwtHandler;

    public function __construct() {
        $this->model = new ProductModel();
        $this->jwtHandler = new JWTHandler(); [cite: 6951]
    }

    // HÀM KIỂM TRA BẢO MẬT: Bắt buộc phải có Token mới cho vô
    private function authenticate() {
        $headers = apache_request_headers(); [cite: 6951]
        if (isset($headers['Authorization'])) { [cite: 6952]
            $authHeader = $headers['Authorization']; [cite: 6952]
            $arr = explode(" ", $authHeader); [cite: 6953]
            $jwt = $arr[1] ?? null; [cite: 6953]
            
            if ($jwt) {
                $decoded = $this->jwtHandler->decode($jwt); [cite: 6954]
                return $decoded ? true : false; [cite: 6955]
            }
        }
        return false; [cite: 6956]
    }

    // API 1: Lấy danh sách Waifu (Có bảo vệ JWT) [cite: 6710, 6956]
    public function index() {
        if ($this->authenticate()) { [cite: 6956]
            header('Content-Type: application/json'); [cite: 6956]
            $products = $this->model->getAll(); // Lấy từ Model của Sếp [cite: 6957]
            echo json_encode($products); [cite: 6957]
        } else {
            http_response_code(401); [cite: 6957]
            echo json_encode(['message' => 'Bạn chưa đăng nhập hoặc Token hết hạn (Unauthorized)']); [cite: 6958]
        }
    }

    // API 2: Chức năng Đăng nhập để phát Token [cite: 6994]
    public function apiLogin() {
        header('Content-Type: application/json'); [cite: 6994]
        $data = json_decode(file_get_contents("php://input"), true); [cite: 6995]
        
        $email = $data['email'] ?? ''; 
        $password = $data['password'] ?? ''; 
        
        $user = $this->model->loginUser($email); // Xài lại hàm login cũ của Sếp
        
        if ($user && password_verify($password, $user['password'])) { [cite: 6996]
            // Tạo Token chứa ID và Email của Sếp [cite: 6996]
            $token = $this->jwtHandler->encode(['id' => $user['id'], 'email' => $user['email'], 'role' => $user['role']]); [cite: 6996]
            echo json_encode(['message' => 'Đăng nhập API thành công!', 'token' => $token]); [cite: 6997]
        } else {
            http_response_code(401); [cite: 6997]
            echo json_encode(['message' => 'Sai email hoặc mật khẩu']); [cite: 6998]
        }
    }
    // BÀI 5.3: API Thêm sản phẩm (POST)
    public function apiCreate() {
        if ($this->authenticate()) { // Bắt buộc phải có Token
            $data = json_decode(file_get_contents("php://input"), true);
            
            // Lấy dữ liệu từ JSON khách gửi lên
            $name = $data['name'] ?? '';
            $price = $data['price'] ?? 0;
            $brand = $data['brand'] ?? 'Khác';
            $image = $data['image'] ?? '';
            $stock = $data['stock'] ?? 10;
            
            if(!empty($name) && !empty($price)) {
                // Gọi model để lưu vô DB (Sử dụng các cột mặc định cho flash sale)
                if($this->model->create($name, $price, $brand, $image, $stock, 0, 0, 0)) {
                    http_response_code(201);
                    echo json_encode(['message' => 'Thêm Waifu thành công!']);
                } else {
                    http_response_code(503);
                    echo json_encode(['message' => 'Lỗi! Không thêm được.']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Thiếu thông tin bắt buộc (name, price)']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Lỗi Token: Bạn không có quyền!']);
        }
    }

    // BÀI 5.4: API Cập nhật sản phẩm (PUT)
    public function apiUpdate() {
        if ($this->authenticate()) {
            $data = json_decode(file_get_contents("php://input"), true);
            
            $id = $data['id'] ?? null;
            $name = $data['name'] ?? '';
            $price = $data['price'] ?? 0;
            $brand = $data['brand'] ?? 'Khác';
            $image = $data['image'] ?? '';
            $stock = $data['stock'] ?? 10;

            if(!empty($id) && !empty($name)) {
                if($this->model->update($id, $name, $price, $brand, $image, $stock, 0, 0, 0)) {
                    http_response_code(200);
                    echo json_encode(['message' => 'Cập nhật Waifu thành công!']);
                } else {
                    http_response_code(503);
                    echo json_encode(['message' => 'Lỗi! Không cập nhật được.']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Thiếu ID hoặc tên sản phẩm']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Lỗi Token: Bạn không có quyền!']);
        }
    }

    // BÀI 5.5: API Xóa sản phẩm (DELETE)
    public function apiDelete() {
        if ($this->authenticate()) {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['id'] ?? null;

            if(!empty($id)) {
                if($this->model->delete($id)) {
                    http_response_code(200);
                    echo json_encode(['message' => 'Đã tiễn Waifu lên đường!']);
                } else {
                    http_response_code(503);
                    echo json_encode(['message' => 'Lỗi! Không xóa được.']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Truyền ID vô mới xóa được chứ!']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Lỗi Token: Bạn không có quyền!']);
        }
    }
    // API: Lấy chi tiết MỘT sản phẩm (GET)
    public function apiDetail() {
        if ($this->authenticate()) {
            // Lấy ID từ URL (vd: index.php?action=api_detail&id=1)
            $id = $_GET['id'] ?? null;
            
            if ($id) {
                $product = $this->model->getById($id); // Gọi hàm getById có sẵn trong Model
                if ($product) {
                    header('Content-Type: application/json');
                    http_response_code(200);
                    echo json_encode($product);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'Không tìm thấy bé Waifu này!']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Vui lòng cung cấp ID sản phẩm!']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Lỗi Token: Bạn không có quyền!']);
        }
    }
}
?>