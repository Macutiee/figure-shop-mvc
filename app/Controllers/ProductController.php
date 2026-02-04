<?php
require_once __DIR__ . '/../Models/ProductModel.php';

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    // Trang chủ: Liệt kê danh sách
    public function index() {
        $products = $this->model->getAll();
        require 'app/Views/product/list.php'; // Gọi View hiển thị
    }

    // Xử lý thêm mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Giả sử lấy ảnh URL cho đơn giản, thực tế cần code upload file
            $this->model->create($_POST['name'], $_POST['price'], $_POST['brand'], $_POST['image']);
            header("Location: index.php"); // Quay về trang chủ
        }
    }

    // Trang sửa (Form)
    public function edit($id) {
        $product = $this->model->getById($id);
        require 'app/Views/product/edit.php';
    }

    // Xử lý cập nhật
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model->update($id, $_POST['name'], $_POST['price'], $_POST['brand'], $_POST['image']);
            header("Location: index.php");
        }
    }

    // Xử lý xóa
    public function destroy($id) {
        $this->model->delete($id);
        header("Location: index.php");
    }
}
?>