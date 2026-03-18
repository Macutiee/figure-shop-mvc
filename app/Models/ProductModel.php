<?php
require_once __DIR__ . '/../Core/Database.php';

class ProductModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM products ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm mới (Đã có đủ cột Flash Sale)
    public function create($name, $price, $brand, $image, $stock, $old_price = 0, $sold_count = 0, $is_flash_sale = 0) {
        $query = "INSERT INTO products (name, price, brand, image, stock, old_price, sold_count, is_flash_sale) 
                  VALUES (:name, :price, :brand, :image, :stock, :old_price, :sold_count, :is_flash_sale)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'name' => $name, 'price' => $price, 'brand' => $brand, 'image' => $image, 'stock' => $stock,
            'old_price' => $old_price, 'sold_count' => $sold_count, 'is_flash_sale' => $is_flash_sale
        ]);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật (Đã có đủ cột Flash Sale)
    public function update($id, $name, $price, $brand, $image, $stock, $old_price = 0, $sold_count = 0, $is_flash_sale = 0) {
        $query = "UPDATE products SET name=:name, price=:price, brand=:brand, image=:image, stock=:stock, 
                  old_price=:old_price, sold_count=:sold_count, is_flash_sale=:is_flash_sale WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'name' => $name, 'price' => $price, 'brand' => $brand, 'image' => $image, 'stock' => $stock,
            'old_price' => $old_price, 'sold_count' => $sold_count, 'is_flash_sale' => $is_flash_sale, 'id' => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function search($keyword) {
        $sql = "SELECT * FROM products WHERE name LIKE :keyword OR brand LIKE :keyword ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // HÀM LƯU ĐƠN HÀNG VÀ TRỪ TỒN KHO (Đã đổi thành $this->conn)
    public function saveOrder($name, $phone, $address, $note, $total, $cart) {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("INSERT INTO orders (customer_name, customer_phone, customer_address, customer_note, total_price) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $phone, $address, $note, $total]);
            
            $orderId = $this->conn->lastInsertId();

            foreach ($cart as $productId => $item) {
                $stmtDetail = $this->conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmtDetail->execute([$orderId, $productId, $item['quantity'], $item['price']]);

                // Trừ kho và tăng thanh "Đã bán"
                $stmtUpdate = $this->conn->prepare("UPDATE products SET stock = stock - ?, sold_count = sold_count + ? WHERE id = ?");
                $stmtUpdate->execute([$item['quantity'], $item['quantity'], $productId]);
            }

            $this->conn->commit(); 
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack(); 
            return false;
        }
    }
    // --- CÁC HÀM CHO TRANG DASHBOARD ADMIN ---

    // 1. Tính tổng doanh thu
    public function getTotalRevenue() {
        $stmt = $this->conn->prepare("SELECT SUM(total_price) as total FROM orders");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? $result['total'] : 0;
    }

    // 2. Đếm tổng số đơn hàng
    public function getTotalOrders() {
        $stmt = $this->conn->prepare("SELECT COUNT(id) as total FROM orders");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? $result['total'] : 0;
    }

    // 3. Lấy danh sách tài khoản khách hàng
    public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // HÀM KIỂM TRA ĐĂNG NHẬP (BẢN NÂNG CẤP BẢO MẬT - BĂM MẬT KHẨU)
    public function checkLogin($email, $password, $role) {
        // 1. Chạy vô kho tìm coi có cái email và đúng chức vụ (role) này không
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email AND role = :role");
        $stmt->execute(['email' => $email, 'role' => $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Dùng hàm password_verify của PHP để ép mật khẩu khách nhập vào khớp với cái nùi đã băm trong DB
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Khớp thì mở cửa cho vô
        }
        return false; // Sai thì đuổi ra
    }
    // --- HÀM CHO BIỂU ĐỒ TRÒN ---
    // 4. Lấy danh số bán được theo từng Hãng Figure
    public function getSalesByBrand() {
        // Gom nhóm theo hãng và cộng số lượng 'đã bán' (sold_count)
        $stmt = $this->conn->prepare("
            SELECT brand, SUM(sold_count) as total_sold
            FROM products
            WHERE sold_count > 0  -- Chỉ lấy những hãng đã bán được hàng
            GROUP BY brand
            ORDER BY total_sold DESC  -- Sắp xếp hãng bán chạy nhất lên đầu
            LIMIT 5  -- Chỉ lấy Top 5 hãng cho biểu đồ nó đẹp
        ");
        $stmt->execute();
        // Lấy ra một cái mảng dữ liệu (e.g., [['brand' => 'SEGA', 'total_sold' => 10], ...])
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // --- CÁC HÀM XỬ LÝ SỬA USER ---
    // Lấy thông tin 1 người cụ thể
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật thông tin người đó
    public function updateUser($id, $fullname, $email, $phone, $role) {
        $stmt = $this->conn->prepare("UPDATE users SET fullname = :fullname, email = :email, phone = :phone, role = :role WHERE id = :id");
        return $stmt->execute(['fullname' => $fullname, 'email' => $email, 'phone' => $phone, 'role' => $role, 'id' => $id]);
    }
    // LƯU NGƯỜI MỚI ĐĂNG KÝ VÀO DB
    public function registerUser($fullname, $email, $password, $phone, $role) {
        $stmt = $this->conn->prepare("INSERT INTO users (fullname, email, password, phone, role) VALUES (:fullname, :email, :password, :phone, :role)");
        return $stmt->execute([
            'fullname' => $fullname,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'role' => $role
        ]);
    }
    // TÌM TÀI KHOẢN BẰNG EMAIL
    public function loginUser($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // CẬP NHẬT HỒ SƠ ADMIN TỪ DASHBOARD
    public function updateAdminProfile($id, $fullname, $password) {
        if (!empty($password)) {
            // Nếu Sếp có nhập pass mới thì băm nó ra rồi lưu chung với tên
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE users SET fullname = :fullname, password = :password WHERE id = :id");
            return $stmt->execute([
                'fullname' => $fullname,
                'password' => $hashed_password,
                'id' => $id
            ]);
        } else {
            // Nếu Sếp không nhập pass thì chỉ cập nhật cái tên thôi
            $stmt = $this->conn->prepare("UPDATE users SET fullname = :fullname WHERE id = :id");
            return $stmt->execute([
                'fullname' => $fullname,
                'id' => $id
            ]);
        }
    }
    // LẤY TẤT CẢ ĐƠN HÀNG (Mới nhất lên đầu)
    public function getAllOrders() {
        $stmt = $this->conn->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $orderId]);
    }
}
?>