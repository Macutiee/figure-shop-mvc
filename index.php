<?php
session_start();

// TỰ ĐỘNG XÁC ĐỊNH ĐƯỜNG DẪN GỐC CỦA WEBSITE (VÍ DỤ: /figure-shop-mvc)
// ĐỂ MỌI LINK ẢNH LUÔN CHÍNH XÁC
define('BASE_URL', str_replace('\\', '/', substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']))));

require_once __DIR__ . '/app/Controllers/ProductController.php';

$controller = new ProductController();
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'admin':
        $controller->admin();
        break;
    case 'store':
        $controller->store();
        break;
    case 'edit':
        $controller->edit($id);
        break;
    case 'update':
        $controller->update($id);
        break;
    case 'delete':
        $controller->destroy($id);
        break;
        
    case 'add_to_cart':
        $controller->addToCart($id);
        break;

    case 'buy_now':
        $controller->buyNow($id);
        break;
        
    case 'cart':
        $controller->cart();
        break;

    case 'remove_from_cart':
        $controller->removeFromCart($id);
        break;

    case 'update_cart':
        $quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
        $controller->updateCart($id, $quantity);
        break;

    case 'checkout':
        $controller->checkout();
        break;
    case 'process_checkout':
        $controller->processCheckout();
        break;

    case 'dashboard':
        $controller->dashboard();
        break;
    
    case 'login':
        $controller->login();
        break;
    case 'process_login':
        $controller->processLogin();
        break;
    case 'logout':
        $controller->logout();
        break;

    case 'edit_user':
        $controller->editUser($id);
        break;
    case 'update_user':
        $controller->updateUser($id);
        break;
        
    case 'process_register':
        $controller->processRegister();
        break;
    case 'manage_orders':
        $controller->orderManagement();
        break;
    case 'update_status':
        $controller->updateStatus();
        break;

    case 'forgot_password':
        $controller->forgotPassword();
        break;
    case 'send_otp':
        $controller->sendOtp();
        break;
    case 'verify_otp':
        $controller->verifyOtp();
        break;
    case 'update_profile':
        $controller->processUpdateProfile();
        break;

    case 'reset_password':
        $controller->resetPassword();
        break;
    case 'profile':
        $controller->profile();
        break;

        // --- KHÚC DÀNH CHO API ---
    case 'api_login':
        require_once 'app/Controllers/ProductApiController.php';
        $apiController = new ProductApiController();
        $apiController->apiLogin();
        break;

    case 'api_products':
        require_once 'app/Controllers/ProductApiController.php';
        $apiController = new ProductApiController();
        $apiController->index();
        break;
    case 'api_create':
        require_once 'app/Controllers/ProductApiController.php';
        (new ProductApiController())->apiCreate();
        break;
    case 'api_update':
        require_once 'app/Controllers/ProductApiController.php';
        (new ProductApiController())->apiUpdate();
        break;
    case 'api_delete':
        require_once 'app/Controllers/ProductApiController.php';
        (new ProductApiController())->apiDelete();
        break;
    case 'api_detail':
        require_once 'app/Controllers/ProductApiController.php';
        (new ProductApiController())->apiDetail();
        break;
    case 'api_categories':
        require_once 'app/Controllers/CategoryApiController.php';
        (new CategoryApiController())->index();
        break;

    // --- KHÚC DÀNH CHO API CỦA DASHBOARD ---
    case 'api_auth_me':
        require_once 'app/Controllers/DashboardApiController.php';
        (new DashboardApiController())->authMe();
        break;
    case 'api_dashboard_stats':
        require_once 'app/Controllers/DashboardApiController.php';
        (new DashboardApiController())->stats();
        break;
    case 'api_users':
        require_once 'app/Controllers/DashboardApiController.php';
        (new DashboardApiController())->users();
        break;
    case 'api_dashboard_charts':
        require_once 'app/Controllers/DashboardApiController.php';
        (new DashboardApiController())->charts();
        break;
    case 'api_update_profile':
        require_once 'app/Controllers/DashboardApiController.php';
        (new DashboardApiController())->updateProfile();
        break;
    case 'api_logout':
        require_once 'app/Controllers/DashboardApiController.php';
        (new DashboardApiController())->logout();
        break;
        
    default:
        $controller->index();
        break;
}
?>