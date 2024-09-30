<?php

class AdminController{

    public function dashboard() {
        // Logic to handle home page display, like fetching data
        include '../views/admin/dashboard.php';
    }

    public function admin_login() {
        // Logic to handle home page display, like fetching data
        include '../views/admin/admin_login.php';
    }

    public function products() {
        // Logic to handle home page display, like fetching data
        include '../views/admin/products.php';
    }

    public function placed_orders() {
        include '../views/admin/placed_orders.php';
    }
    
    public function admin_accounts() {
        include '../views/admin/placed_orders.php';
    }

    public function register_admin() {
        include '../views/admin/register_admin.php';
    }

    public function messages() {
        include '../views/admin/messages.php';
    }

    public function update_profile() {
        include '../views/admin/update_profile.php';
    }

    public function update_product() {
        include '../views/admin/update_product.php';
    }

    public function users_accounts() {
        include '../views/admin/users_accounts.php';
    }
}