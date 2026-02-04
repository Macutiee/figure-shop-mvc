<!DOCTYPE html>
<html lang="vi">
<style>
    /* Hiệu ứng khi di chuột vào ảnh */
    .figure-img {
        transition: all 0.3s ease-in-out; /* Chuyển động mượt trong 0.3s */
        cursor: pointer; /* Đổi con trỏ chuột thành hình bàn tay */
        object-fit: cover; /* Giữ ảnh không bị méo */
    }

    .figure-img:hover {
        transform: scale(3); /* Phóng to gấp 3 lần */
        z-index: 100; /* Đè lên tất cả các phần tử khác */
        position: relative; /* Bắt buộc có để z-index hoạt động */
        border: 2px solid #fff; /* Thêm viền trắng cho nổi */
        box-shadow: 0 10px 20px rgba(0,0,0,0.5); /* Đổ bóng cho đẹp */
    }
</style>
<head>
    <meta charset="UTF-8">
    <title>Quản lý Figure Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Danh sách Figure (Mô hình)</h2>
        
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
            + Thêm Figure Mới
        </button>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên Figure</th>
                            <th>Hãng (Brand)</th>
                            <th>Giá (VND)</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td class="text-center align-middle">
                                <img src="<?= $row['image'] ?>" 
                                class="img-thumbnail figure-img" 
                                width="60" 
                                height="60"
                                onclick="viewImage('<?= $row['image'] ?>', '<?= htmlspecialchars($row['name']) ?>')">
                            </td>
                            <td><?= $row['name'] ?></td>
                            <td><span class="badge bg-info"><?= $row['brand'] ?></span></td>
                            <td class="fw-bold text-danger"><?= number_format($row['price']) ?> đ</td>
                            <td>
                                <a href="index.php?action=edit&id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="index.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Xóa hả?')" class="btn btn-danger btn-sm">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="index.php?action=store" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm Figure Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tên Figure</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Hãng (Brand)</label>
                            <input type="text" name="brand" class="form-control" placeholder="Ví dụ: Good Smile">
                        </div>
                        <div class="mb-3">
                            <label>Giá</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Link Ảnh</label>
                            <input type="text" name="image" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered"> <div class="modal-content bg-transparent border-0"> <div class="modal-body text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                
                <img id="modalImageSrc" src="" class="img-fluid rounded shadow-lg d-block mx-auto" style="max-height: 80vh;">
                
                <h5 id="modalImageTitle" class="text-white mt-3 bg-dark d-inline-block px-3 py-1 rounded"></h5>
            </div>

            </div>
        </div>
    </div>
    <script>
    // Hàm này chạy khi bấm vào ảnh nhỏ
    function viewImage(linkAnh, tenFigure) {
        // 1. Gán link ảnh vào khung Modal
        document.getElementById('modalImageSrc').src = linkAnh;
        
        // 2. Gán tên Figure vào dưới ảnh
        document.getElementById('modalImageTitle').innerText = tenFigure;
        
        // 3. Bật Modal lên
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }
    </script>
</body>
</html>