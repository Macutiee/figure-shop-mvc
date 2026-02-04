<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Cập nhật Figure: <?= htmlspecialchars($product['name']) ?></h4>
                    </div>
                    <div class="card-body">
                        <form action="index.php?action=update&id=<?= $product['id'] ?>" method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên Figure</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Giá (VND)</label>
                                    <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Hãng (Brand)</label>
                                    <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($product['brand']) ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Link Ảnh</label>
                                <input type="text" name="image" class="form-control" value="<?= htmlspecialchars($product['image']) ?>">
                                <div class="mt-2">
                                    <small class="text-muted">Ảnh hiện tại:</small><br>
                                    <img src="<?= $product['image'] ?>" height="100" class="rounded border mt-1">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-secondary">Quay lại</a>
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>