<?php

// app/controllers/UserController.php
class UserController {
    public function home() {
        // Logic to handle home page display, like fetching data
        include '../views/user/home.php';
    }

    public function login() {
        include '../views/user/login.php';
    }

    public function register() {
        include '../views/user/register.php';
    }

    public function menu() {
        include '../views/user/menu.php';
    }

    public function orders() {
        include '../views/user/orders.php';
    }

    public function category() {
        include '../views/user/category.php';
    }

    public function checkout() {
        include '../views/user/checkout.php';
    }

    public function contact() {
        include '../views/user/contact.php';
    }

    public function about() {
        include '../views/user/about.php';
    }

    public function profile() {
        include '../views/user/profile.php';
    }

    public function quick_view() {
        include '../views/user/quick_view.php';
    }

    public function search() {
        include '../views/user/search.php';
    }

    public function update_address() {
        include '../views/user/update_address.php';
    }

    public function update_profile() {
        include '../views/user/update_profile.php';
    }

    public function cart() {
        include '../views/user/update_profile.php';
    }


}