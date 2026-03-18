<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Models/ProductModel.php';

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    public function index() {
        if (isset($_GET['search']) && trim($_GET['search']) != '') {
            $keyword = trim($_GET['search']);
            $products = $this->model->search($keyword);
        } else {
            $products = $this->model->getAll(); 
        }
        require_once __DIR__ . '/../Views/product/customer.php'; 
    }

    public function admin() {
        if (isset($_GET['search']) && trim($_GET['search']) != '') {
            $keyword = trim($_GET['search']);
            $products = $this->model->search($keyword);
        } else {
            $products = $this->model->getAll(); 
        }
        require_once __DIR__ . '/../Views/product/list.php';
    }
    // XỬ LÝ ĐĂNG NHẬP (BẢN CHUẨN)
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Nhờ Model tìm coi có ai xài Email này không
            $user = $this->model->loginUser($email);

            // Kiểm tra: Có user đó KHÔNG? VÀ Mật khẩu gõ vô có khớp với mã băm trong DB KHÔNG?
            if ($user && password_verify($password, $user['password'])) {
                // Đăng nhập thành công -> Lưu vô Session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'fullname' => $user['fullname'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                
                // Phân luồng: Admin thì lùa vô Dashboard, Khách thì thả ra Trang chủ
                if ($user['role'] === 'admin') {
                    echo "<script>alert('Chào mừng Sếp quay lại!'); window.location.href='index.php?action=dashboard';</script>";
                } else {
                    echo "<script>alert('Đăng nhập thành công! Lụm Waifu thôi!'); window.location.href='index.php';</script>";
                }
                exit;
            } else {
                // Sai pass hoặc sai email
                echo "<script>alert('Sai Email hoặc Mật khẩu rồi nha! Check lại cái coi!'); window.location.href='index.php?action=login';</script>";
            }
        }
    }
    // XỬ LÝ ĐĂNG KÝ TÀI KHOẢN MỚI
    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            
            // BĂM MẬT KHẨU CỰC MẠNH NGAY TẠI ĐÂY (Sếp khỏi lo lộ pass nữa)
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            // Khách tự đăng ký thì mặc định role là 'customer'
            $role = 'customer';

            // Lưu vô DB
            $this->model->registerUser($fullname, $email, $password, $phone, $role);
            
            echo "<script>alert('Chào mừng khách yêu! Đăng ký thành công nha! 🎉'); window.location.href='index.php?action=login';</script>";
        }
    }

    // GỌI GIAO DIỆN SỬA USER
    public function editUser($id) {
        $user = $this->model->getUserById($id);
        require_once __DIR__ . '/../Views/product/edit_user.php';
    }

    // XỬ LÝ NÚT LƯU KHI SỬA
    public function updateUser($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $role = $_POST['role'];

            $this->model->updateUser($id, $fullname, $email, $phone, $role);
            
            echo "<script>alert('Cập nhật hồ sơ thành công nha Sếp! ✨'); window.location.href='index.php?action=dashboard';</script>";
        }
    }

    public function dashboard() {
        // 1. Lấy dữ liệu thật từ Database
        $totalRevenue = $this->model->getTotalRevenue() ?? 0;
        $totalOrders = $this->model->getTotalOrders() ?? 0;
        $users = $this->model->getAllUsers() ?? [];
        $salesByBrandRawData = $this->model->getSalesByBrand() ?? [];

        // 2. Xử lý dữ liệu biểu đồ tròn Top hãng
        $brandLabels = array_column($salesByBrandRawData, 'brand');
        $brandSales = array_column($salesByBrandRawData, 'total_sold');

        // 3. Giữ lại 3 biến giả lập CẦN THIẾT cho Stat Cards & Header
        $todayRevenue = 750000; 
        $newOrdersToday = 34; 
        $revenueTargetPercent = 71; // Giữ lại để hiện trên Progress Bar mục tiêu

        // Gọi giao diện "siêu phẩm" dịu ngọt mới ra
        require_once __DIR__ . '/../Views/product/dashboard.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $brand = trim($_POST['brand']);
            $price = $_POST['price'];
            $image = $_POST['image'];
            $stock = $_POST['stock']; 
            
            // Hứng thêm 3 biến Flash Sale
            $old_price = isset($_POST['old_price']) ? $_POST['old_price'] : 0;
            $sold_count = isset($_POST['sold_count']) ? $_POST['sold_count'] : 0;
            $is_flash_sale = isset($_POST['is_flash_sale']) ? $_POST['is_flash_sale'] : 0;

            $this->model->create($name, $price, $brand, $image, $stock, $old_price, $sold_count, $is_flash_sale);
            
            header("Location: index.php?action=admin");
            exit();
        }
    }

    public function edit($id) {
        $product = $this->model->getById($id);
        require 'app/Views/product/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $brand = trim($_POST['brand']);
            $price = $_POST['price'];
            $image = $_POST['image'];
            $stock = $_POST['stock']; 

            // Hứng thêm 3 biến Flash Sale
            $old_price = isset($_POST['old_price']) ? $_POST['old_price'] : 0;
            $sold_count = isset($_POST['sold_count']) ? $_POST['sold_count'] : 0;
            $is_flash_sale = isset($_POST['is_flash_sale']) ? $_POST['is_flash_sale'] : 0;

            $this->model->update($id, $name, $price, $brand, $image, $stock, $old_price, $sold_count, $is_flash_sale);
            
            header("Location: index.php?action=admin");
            exit();
        }
    }

    public function destroy($id) {
        $this->model->delete($id);
        header("Location: index.php");
    }

    public function addToCart($id) {
        $product = $this->model->getById($id);

        if ($product && $product['stock'] > 0) { 
            if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

            $currentQty = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['quantity'] : 0;

            if ($currentQty < $product['stock']) { 
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] += 1; 
                } else {
                    $_SESSION['cart'][$id] = [
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'image' => $product['image'],
                        'quantity' => 1,
                        'stock' => $product['stock'] 
                    ];
                }
            } else {
                echo "<script>alert('Món này trong kho chỉ còn ".$product['stock']." cái thôi!'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('Sản phẩm này đã hết hàng!'); window.history.back();</script>";
            exit();
        }
        
        header("Location: index.php");
        exit();
    }

    public function cart() {
        require_once __DIR__ . '/../Views/product/cart.php';
    }

    public function removeFromCart($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]); 
        }
        header("Location: index.php?action=cart");
        exit();
    }

    public function updateCart($id, $quantity) {
        if (isset($_SESSION['cart'][$id])) {
            $maxStock = $_SESSION['cart'][$id]['stock']; 
            
            if ($quantity > $maxStock) {
                $_SESSION['cart'][$id]['quantity'] = $maxStock;
                echo "<script>alert('Chỉ có thể mua tối đa $maxStock sản phẩm!'); window.location.href='index.php?action=cart';</script>";
                exit();
            } elseif ($quantity > 0) {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
        header("Location: index.php?action=cart");
        exit();
    }

    public function buyNow($id) {
        $product = $this->model->getById($id);

        if ($product && $product['stock'] > 0) { 
            if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
            $currentQty = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['quantity'] : 0;

            if ($currentQty < $product['stock']) { 
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] += 1; 
                } else {
                    $_SESSION['cart'][$id] = [
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'image' => $product['image'],
                        'quantity' => 1,
                        'stock' => $product['stock']
                    ];
                }
            } else {
                echo "<script>alert('Món này trong kho chỉ còn ".$product['stock']." cái thôi!'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('Sản phẩm này đã hết hàng!'); window.history.back();</script>";
            exit();
        }
        
        header("Location: index.php?action=cart");
        exit();
    }

    public function checkout() {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header("Location: index.php");
            exit();
        }
        require_once __DIR__ . '/../Views/product/checkout.php';
    }

    // HÀM XỬ LÝ KHI KHÁCH BẤM CHỐT ĐƠN (Đã dọn dẹp sạch sẽ)
    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            
            $name = htmlspecialchars($_POST['customer_name']);
            $phone = htmlspecialchars($_POST['customer_phone']);
            $address = htmlspecialchars($_POST['customer_address']);
            $note = isset($_POST['customer_note']) ? htmlspecialchars($_POST['customer_note']) : '';

            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Gọi Model để lưu xuống Database (Đã sửa lại thành $this->model)
            $success = $this->model->saveOrder($name, $phone, $address, $note, $total, $_SESSION['cart']);

            if ($success) {
                unset($_SESSION['cart']); // Xóa sạch giỏ hàng
                echo "<script>
                    alert('🎉 Đặt hàng thành công!\\nWaifu sẽ được gửi tới: $address cho bạn $name.\\nCảm ơn bạn đã rước các ẻm!');
                    window.location.href = 'index.php';
                </script>";
                exit();
            } else {
                echo "<script>alert('Lỗi hệ thống! Không thể đặt hàng lúc này.'); window.history.back();</script>";
                exit();
            }
        } else {
            header("Location: index.php?action=cart");
            exit();
        }
    }
    // HIỂN THỊ TRANG ĐĂNG NHẬP
    public function login() {
        require_once __DIR__ . '/../Views/product/login.php';
    }


    // HÀM ĐĂNG XUẤT
    public function logout() {
        unset($_SESSION['user']);
        header("Location: index.php");
        exit();
    }
    // 1. HIỂN THỊ FORM NHẬP EMAIL QUÊN MẬT KHẨU
    public function forgotPassword() {
        require_once __DIR__ . '/../Views/product/forgot_password.php';
    }

    // 2. XỬ LÝ GỬI MÃ OTP VÀO GMAIL
    public function sendOtp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $user = $this->model->loginUser($email);

            if ($user) {
                // Tạo mã OTP 6 số ngẫu nhiên
                $otp = rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['reset_email'] = $email;

                // --- BẮT ĐẦU GỬI MAIL ---
                // --- BẮT ĐẦU GỬI MAIL ---
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'baoloann2906@gmail.com'; 
                    
                    $mail->Password   = 'mylcptbfumptfyof'; 
                    
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;

                    $mail->setFrom('baoloann2906@gmail.com', "Ma's Figure Paradise");
                    
                    $mail->addAddress($email); // Gửi tới email khách vừa nhập
                    
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = 'Mã OTP Khôi Phục Mật Khẩu';
                    $mail->Body    = "Chào bạn,<br>Mã OTP để đặt lại mật khẩu của bạn là: <b style='color:red; font-size:20px;'>$otp</b><br>Mã này có hiệu lực ngay lập tức. Vui lòng không chia sẻ cho ai!";

                    $mail->send();
                    echo "<script>alert('Mã OTP đã được gửi cái Tinh! Check mail nha!'); window.location.href='index.php?action=verify_otp';</script>";
                } catch (Exception $e) {
                    echo "<script>alert('Lỗi gửi mail: {$mail->ErrorInfo}'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('Email này chưa từng mua Waifu ở shop nha!'); window.history.back();</script>";
            }
        }
    }
    // HIỂN THỊ DANH SÁCH ĐƠN HÀNG TRONG ADMIN
    public function orderManagement() {
        $orders = $this->model->getAllOrders();
        require_once __DIR__ . '/../Views/product/order_list.php';
    }

    // XỬ LÝ ĐỔI TRẠNG THÁI ĐƠN HÀNG
    public function updateStatus() {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $this->model->updateOrderStatus($_GET['id'], $_GET['status']);
            echo "<script>alert('Đã cập nhật trạng thái đơn hàng!'); window.location.href='index.php?action=manage_orders';</script>";
        }
    }

    // 3. HIỂN THỊ FORM NHẬP OTP & ĐẶT LẠI PASS
    public function verifyOtp() {
        require_once __DIR__ . '/../Views/product/verify_otp.php';
    }

    // XỬ LÝ KHI SẾP BẤM NÚT LƯU Ở MODAL HỒ SƠ
    public function processUpdateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $id = $_SESSION['user']['id'];
            $fullname = trim($_POST['fullname']);
            $new_password = $_POST['new_password'];

            // Nhờ Model lưu xuống kho
            $this->model->updateAdminProfile($id, $fullname, $new_password);

            // Lưu xong thì tự cập nhật luôn cái Tên mới ở trên góc màn hình cho Sếp thấy
            $_SESSION['user']['fullname'] = $fullname;

            echo "<script>alert('Tuyệt vời Sếp ơi! Cập nhật hồ sơ thành công! ✨'); window.location.href='index.php?action=dashboard';</script>";
        }
    }
}
?>