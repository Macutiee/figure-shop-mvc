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

            $this->model->create($name, $price, $brand, $image, $stock);
            
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

            $this->model->update($id, $name, $price, $brand, $image, $stock);
            
            header("Location: index.php?action=admin");
            exit();
        }
    }

    // Xử lý xóa
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
        
        // KHÁC BIỆT Ở ĐÂY: Đá khách sang thẳng trang Giỏ Hàng (Cart)
        header("Location: index.php?action=cart");
        exit();
    }
    // 1. Hàm hiển thị form nhập thông tin giao hàng
    public function checkout() {
        // Nếu giỏ hàng trống thì không cho vào trang thanh toán, đá về trang chủ
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header("Location: index.php");
            exit();
        }
        // Gọi file giao diện thanh toán ra
        require_once __DIR__ . '/../Views/product/checkout.php';
    }

    // 2. Hàm xử lý khi khách bấm nút "Đặt Hàng"
    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy thông tin khách vừa nhập
            $fullname = htmlspecialchars($_POST['fullname']);
            $phone = htmlspecialchars($_POST['phone']);
            $address = htmlspecialchars($_POST['address']);

            // Lưu ý: Đáng lẽ chỗ này mình sẽ viết code lưu vào Database (bảng orders).
            // Nhưng tạm thời mình sẽ giả lập Đặt hàng thành công & Xóa sạch giỏ hàng nha!

            unset($_SESSION['cart']); // Đặt xong thì dọn sạch giỏ hàng

            // Hiện thông báo thành công và đá về trang chủ
            echo "<script>
                alert('🎉 Tuyệt vời! Bạn đã đặt hàng thành công.\\nShipper sẽ giao đến địa chỉ: $address cho bạn $fullname.\\nCảm ơn bạn đã mua sắm!');
                window.location.href = 'index.php';
            </script>";
            exit();
        }
    }
}
?>