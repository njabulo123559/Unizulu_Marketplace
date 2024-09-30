<?php
// Simple routing logic for your application
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    // User Routes
    case 'home':
        include '../app/views/user/home.php';
        break;
    case 'login':
        include '../app/views/user/login.php';
        break;
    case 'register':
        include '../app/views/user/register.php';
        break;
    case 'menu':
        include '../app/views/user/menu.php';
        break;
    case 'orders':
        include '../app/views/user/orders.php';
        break;
    case 'category':
        include '../app/views/user/category.php';
        break;
    case 'checkout':
        include '../app/views/user/checkout.php';
        break;
    case 'contact':
        include '../app/views/user/contact.php';
        break;
    case 'about':
        include '../app/views/user/about.php';
        break;
    case 'profile':
        include '../app/views/user/profile.php';
        break;
    case 'quick_view':
        include '../app/views/user/quick_view.php';
        break;
    case 'search':
        include '../app/views/user/search.php';
        break;
    case 'update_address':
        include '../app/views/user/update_address.php';
        break;
    case 'update_profile':
        include '../app/views/user/update_profile.php';
        break;
    case 'cart':
        include '../app/views/user/cart.php';
        break;

    // Admin Routes
    case 'dashboard':
        include '../app/views/admin/dashboard.php';
        break;
    case 'admin_login':
        include '../app/views/admin/admin_login.php';
        break;
    case 'products':
        include '../app/views/admin/products.php';
        break;
    case 'admin_orders':
        include '../app/views/admin/placed_orders.php';
        break;
    case 'admin_users':
        include '../app/views/admin/users_accounts.php';
        break;
    case 'admin_admins':
        include '../app/views/admin/admin_accounts.php';
        break;
    case 'admin_messages':
        include '../app/views/admin/messages.php';
        break;
    case 'admin_profile':
        include '../app/views/admin/update_profile.php';
        break;
    case 'placed_orders':
        include '../app/views/admin/placed_orders.php';
        break;
    case 'admin_accounts':
        include '../app/views/admin/admin_accounts.php';
        break;
    case 'register_accounts':
        include '../app/views/admin/register_admin.php';
        break;
    case 'messages':
        include '../app/views/admin/messages.php';
        break;
    case 'update_profile':
        include '../app/views/admin/update_profile.php';
        break;
    case 'update_product':
        include '../app/views/admin/update_product.php';
        break;
    case 'users_accounts':
        include '../app/views/admin/users_accounts.php';
        break;

    // Default Route
    default:
        echo 'Page not found!';
        break;
}
