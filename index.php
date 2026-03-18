<?php
session_start();

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
    default:
        $controller->index();
        break;
}
?>