<?php
require_once __DIR__ . '/../Models/ProductModel.php';
require_once __DIR__ . '/../utils/JWTHandler.php'; 

class ProductApiController {
    private $model;
    private $jwtHandler;

    public function __construct() {
        $this->model = new ProductModel();
        $this->jwtHandler = new JWTHandler(); 
    }

    // HÀM KIỂM TRA BẢO MẬT: Bắt buộc phải có Token mới cho vô
    private function authenticate() {
        $headers = apache_request_headers(); 
        if (isset($headers['Authorization'])) { 
            $authHeader = $headers['Authorization']; 
            $arr = explode(" ", $authHeader); 
            $jwt = $arr[1] ?? null; 
            
            if ($jwt) {
                $decoded = $this->jwtHandler->decode($jwt); 
                return $decoded ? true : false; 
            }
        }
        return false; 
    }

    // API 1: Lấy danh sách Waifu (Có bảo vệ JWT) 
    public function index() {
        if ($this->authenticate()) { 
            header('Content-Type: application/json'); 
            $products = $this->model->getAll(); // Lấy từ Model của Sếp 
            echo json_encode($products); 
        } else {
            http_response_code(401); 
            echo json_encode(['message' => 'Bạn chưa đăng nhập hoặc Token hết hạn (Unauthorized)']); 
        }
    }

    // API 2: Chức năng Đăng nhập để phát Token 
    public function apiLogin() {
        header('Content-Type: application/json'); 
        $data = json_decode(file_get_contents("php://input"), true); 
        
        $email = $data['email'] ?? ''; 
        $password = $data['password'] ?? ''; 
        
        error_log("API Login Attempt: Email = " . $email);

        $user = $this->model->loginUser($email); // Xài lại hàm login cũ của Sếp
        
        if ($user) {
            error_log("API Login: User found with email " . $email);
            $isPasswordCorrect = password_verify($password, $user['password']);
            error_log("API Login: Password verification result for " . $email . ": " . ($isPasswordCorrect ? 'Success' : 'Failure'));

            if ($isPasswordCorrect) {
                // Đăng nhập thành công, LƯU VÔ SESSION LUÔN để trang chủ nhận biết
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'fullname' => $user['fullname'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'avatar' => $user['avatar'] ?? null
                ];

                // Tạo Token chứa ID và Email của Sếp 
                $token = $this->jwtHandler->encode(['id' => $user['id'], 'email' => $user['email'], 'role' => $user['role']]); 
                // Trả về cả token và role để JS biết đường điều hướng
                echo json_encode(['message' => 'Đăng nhập thành công!', 'token' => $token, 'role' => $user['role']]); 
            } else {
                http_response_code(401); 
                echo json_encode(['message' => 'Sai email hoặc mật khẩu']); 
            }
        } else {
            error_log("API Login: User not found with email " . $email);
            http_response_code(401); 
            echo json_encode(['message' => 'Sai email hoặc mật khẩu']); 
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