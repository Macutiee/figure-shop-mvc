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
}
?>