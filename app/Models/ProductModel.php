<?php
require_once __DIR__ . '/../Core/Database.php';

class ProductModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // 1. Hiển thị tất cả (Read)
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM products ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm mới 
    public function create($name, $price, $brand, $image, $stock) {
        $query = "INSERT INTO products (name, price, brand, image, stock) VALUES (:name, :price, :brand, :image, :stock)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['name' => $name, 'price' => $price, 'brand' => $brand, 'image' => $image, 'stock' => $stock]);
    }

    // 3. Lấy 1 sản phẩm để sửa (Get by ID)
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Cập nhật (Update)
    public function update($id, $name, $price, $brand, $image, $stock) {
        $query = "UPDATE products SET name=:name, price=:price, brand=:brand, image=:image, stock=:stock WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['name' => $name, 'price' => $price, 'brand' => $brand, 'image' => $image, 'stock' => $stock, 'id' => $id]);
    }

    // 5. Xóa (Delete)
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    // Hàm tìm kiếm theo tên hoặc hãng
    public function search($keyword) {
        // Dùng LIKE để tìm từ khóa có chứa trong tên hoặc hãng
        $sql = "SELECT * FROM products WHERE name LIKE :keyword OR brand LIKE :keyword ORDER BY id DESC";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->execute(['keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>