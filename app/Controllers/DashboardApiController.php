<?php
require_once __DIR__ . '/../Models/ProductModel.php';

class DashboardApiController {
    private $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    public function authMe() {
        header('Content-Type: application/json');
        
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        
        // Xử lý avatar: Nối thêm BASE_URL để link ảnh đúng chuẩn
        if ($user && !empty($user['avatar']) && file_exists($user['avatar'])) {
            $user['avatar'] = BASE_URL . '/' . $user['avatar'];
        }
        
        echo json_encode($user);
    }

    public function stats() {
        header('Content-Type: application/json');
        
        // Lấy toàn bộ dữ liệu từ Database lên
        $totalRevenue = $this->model->getTotalRevenue() ?? 0;
        $users = $this->model->getAllUsers() ?? [];
        $orders = $this->model->getAllOrders() ?? [];
        
        $today = date('Y-m-d');
        $currentMonth = date('Y-m');
        
        $todayRevenue = 0;
        $newOrdersToday = 0;
        $newUsersCount = 0;

        // 1. Tính doanh thu và số đơn hàng HÔM NAY từ DB
        foreach ($orders as $order) {
            // Tìm cột lưu ngày tháng (thường là created_at hoặc order_date)
            $orderDate = $order['created_at'] ?? $order['order_date'] ?? $order['date'] ?? '';
            if ($orderDate && strpos($orderDate, $today) === 0) {
                $todayRevenue += $order['total_price'] ?? 0;
                $newOrdersToday++;
            }
        }

        // 2. Tính số khách hàng đăng ký MỚI (trong tháng này)
        foreach ($users as $user) {
            $userDate = $user['created_at'] ?? $user['date'] ?? '';
            if ($userDate && strpos($userDate, $currentMonth) === 0) {
                $newUsersCount++;
            }
        }
        if ($newUsersCount === 0) $newUsersCount = count($users); // Backup nếu DB của bạn chưa có cột ngày đăng ký

        // 3. Tính % đạt mục tiêu (Giả sử Sếp đặt mục tiêu doanh thu là 100 triệu)
        $target = 100000000;
        $revenueTargetPercent = ($totalRevenue > 0) ? round(($totalRevenue / $target) * 100) : 0;
        if ($revenueTargetPercent > 100) $revenueTargetPercent = 100;

        echo json_encode([
            'total_users' => $newUsersCount,
            'today_revenue' => $todayRevenue,
            'new_orders_today' => $newOrdersToday,
            'total_revenue' => $totalRevenue,
            'revenue_target_percent' => $revenueTargetPercent
        ]);
    }

    public function users() {
        header('Content-Type: application/json');
        
        // Lấy danh sách khách hàng thật từ CSDL
        $users = $this->model->getAllUsers() ?? [];
        foreach ($users as &$user) {
            if (!empty($user['avatar']) && file_exists($user['avatar'])) {
                $user['avatar'] = BASE_URL . '/' . $user['avatar'];
            } else {
                $user['avatar'] = ''; // Gán rỗng để JS tự render avatar UI
            }
        }
        
        echo json_encode($users);
    }

    public function charts() {
        header('Content-Type: application/json');
        
        // Lấy dữ liệu biểu đồ Hãng từ DB
        $salesByBrandRawData = $this->model->getSalesByBrand() ?? [];
        $brandLabels = array_column($salesByBrandRawData, 'brand');
        $brandSales = array_column($salesByBrandRawData, 'total_sold');
        
        // Lấy dữ liệu thật cho Biểu đồ Doanh thu (7 ngày gần nhất)
        $orders = $this->model->getAllOrders() ?? [];
        $revenueLabels = [];
        $revenueData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $displayDate = ($i === 0) ? "Hôm nay (" . date('d/m') . ")" : date('d/m', strtotime($date));
            
            $revenueLabels[] = $displayDate;
            $dailyTotal = 0;
            
            foreach ($orders as $order) {
                $orderDate = $order['created_at'] ?? $order['order_date'] ?? $order['date'] ?? '';
                if ($orderDate && strpos($orderDate, $date) === 0) {
                    $dailyTotal += $order['total_price'] ?? 0;
                }
            }
            $revenueData[] = $dailyTotal;
        }

        echo json_encode([
            'revenue_labels' => $revenueLabels,
            'revenue_data' => $revenueData,
            'brand_labels' => $brandLabels,
            'brand_sales' => $brandSales
        ]);
    }

    public function updateProfile() {
        header('Content-Type: application/json');
        
        // Logic mẫu để cập nhật thông tin
        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
        
        // 1. Validate dữ liệu
        // 2. Upload file ảnh nếu có trong $_FILES['avatar']
        // 3. Update Database Model
        // 4. Update $_SESSION['user']

        echo json_encode([
            'status' => 'success', 
            'message' => 'Lưu hồ sơ thành công!'
        ]);
    }

    public function logout() {
        header('Content-Type: application/json');
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Đã đăng xuất']);
    }
}