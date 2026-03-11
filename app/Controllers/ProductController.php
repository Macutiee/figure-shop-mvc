<?php
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
}
?>