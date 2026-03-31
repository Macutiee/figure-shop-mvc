<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Figure Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* --- GIAO DIỆN ADMIN HỒNG KUTE --- */
        .admin-pink-bg { background-color: #fff0f3; } /* Nền hồng siêu nhạt */
        
        /* Box chứa bảng */
        .card-pink { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(255, 182, 193, 0.4); 
            background: #fff;
        }

        /* Đầu bảng Gradient Hồng */
        .table-pink-header { 
            background: linear-gradient(to right, #ff758f, #ff4d6d) !important; 
        }
        .table-pink-header th { 
            background-color: transparent !important;
            color: white !important; 
            border-bottom: none;
            padding: 15px;
        }

        /* Nút bấm kute */
        .btn-pink { 
            background: linear-gradient(to right, #ff758f, #ff4d6d); 
            color: white; 
            border: none; 
            transition: all 0.3s ease; 
            font-weight: bold;
        }
        .btn-pink:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(255, 77, 109, 0.4); 
            color: white;
        }
        
        .btn-pink-outline { 
            border: 2px solid #ff758f; 
            color: #ff758f; 
            background: transparent; 
            font-weight: bold; 
            transition: all 0.3s ease;
        }
        .btn-pink-outline:hover { 
            background-color: #ff758f; 
            color: white; 
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="admin-pink-bg">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Danh sách Figure (Mô hình)</h2>
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2">
                <a href="index.php?action=dashboard" class="btn btn-secondary rounded-pill px-4">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                </a>
                <button type="button" class="btn btn-pink rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                    + Thêm Figure Mới
                </button>
            </div>
            <form action="index.php" method="GET" class="d-flex w-50">
                <input type="hidden" name="action" value="admin"> 
                
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="Nhập tên Figure hoặc hãng..." 
                       value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                
                <button type="submit" class="btn btn-success">Tìm</button>
                
                <?php if(isset($_GET['search']) && $_GET['search'] != ''): ?>
                    <a href="index.php?action=admin" class="btn btn-secondary ms-2">Hủy</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card card-pink">
            <div class="card-body">
                <table class="table table-hover table-bordered">
    <thead class="table-pink-header text-center align-middle">
    <tr>
        <th>ID</th>
        <th>Ảnh</th>
        <th>Tên Figure</th>
        <th>Hãng (Brand)</th>
        <th>Giá (VND)</th>
        <th>Tồn kho</th>
        <th>Hành động</th>
    </tr>
    </thead>
                    <tbody id="product-list-body">
                        <tr><td colspan="7" class="text-center text-muted">Đang tải dữ liệu Waifu từ kho...</td></tr>
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
                            <label>Số lượng tồn kho</label>
                            <input type="number" name="stock" class="form-control" required min="0" value="10">
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
    document.addEventListener("DOMContentLoaded", function() {
        const token = localStorage.getItem('jwtToken');
        if (!token) {
            alert('Vui lòng đăng nhập!');
            location.href = 'index.php?action=login'; 
            return;
        }

        // Gọi đúng đường dẫn API mà bạn đã định nghĩa trong index.php
        fetch('index.php?action=api_products', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message && data.message.includes('Token')) {
                throw new Error('Token error');
            }
            const tbody = document.getElementById('product-list-body');
            tbody.innerHTML = ''; 

            data.forEach(product => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${product.id}</td>
                    <td class="text-center align-middle">
                        <img src="${product.image}" class="img-thumbnail figure-img" width="60" height="60" 
                             onclick="viewImage('${product.image}', '${product.name.replace(/'/g, "\\'")}')" style="cursor:pointer;">
                    </td>
                    <td>${product.name}</td>
                    <td><span class="badge bg-info">${product.category_name || product.brand}</span></td>
                    <td class="fw-bold text-danger">${Number(product.price).toLocaleString('vi-VN')} đ</td>
                    <td class="text-center fw-bold">${product.stock || 10}</td>
                    <td>
                        <a href="index.php?action=edit&id=${product.id}" class="btn btn-warning btn-sm">Sửa</a>
                        <button onclick="deleteProduct(${product.id})" class="btn btn-danger btn-sm">Xóa</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Phiên đăng nhập API đã hết hạn, vui lòng đăng nhập lại!');
            localStorage.removeItem('jwtToken');
            location.href = 'index.php?action=login';
        });
    });

    function deleteProduct(id) {
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            const token = localStorage.getItem('jwtToken');
            
            // Truyền ID vào body thay vì trên URL vì hàm apiDelete() của bạn đang đọc từ php://input
            fetch(`index.php?action=api_delete`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Product deleted successfully' || data.message === 'Đã tiễn Waifu lên đường!') {
                    location.reload(); 
                } else {
                    alert('Xóa sản phẩm thất bại');
                }
            })
            .catch(error => console.error('Lỗi xóa:', error));
        }
    }
    </script>
</body>
</html>