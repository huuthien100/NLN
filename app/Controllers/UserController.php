<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController
{
    protected $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }
    public function isLogged()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $created_at = date('Y-m-d H:i:s');

            $registered = $this->userModel->register($username, $password, $created_at);

            if ($registered) {
                echo '<script>alert("Đăng ký thành công.");</script>';
                echo '<script>window.location.href = "index.php?page=login";</script>';
            } else {
                echo '<script>alert("Đăng ký thất bại.");</script>';
            }
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $loginResult = $this->userModel->login($username, $password);

            if ($loginResult === 'success') {
                echo '<script>alert("Đăng nhập thành công.");</script>';
                echo '<script>window.location.href = "index.php?page=home";</script>';
            } else {
                echo '<script>alert("' . $loginResult . '");</script>';
            }
        }
    }

    public function logout()
    {
        $this->userModel->logout();
        echo '<script>alert("Đã đăng xuất thành công.");</script>';
        echo '<script>window.location.href = "index.php?page=home";</script>';
    }
}

