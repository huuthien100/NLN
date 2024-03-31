<?php

namespace App\Models;

use PDO;
use PDOException;

class UserModel
{
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserByUsername($username)
    {
        try {
            $query = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['username' => $username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function register($username, $password, $created_at)
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, password, created_at) VALUES (:username, :password, :created_at)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['username' => $username, 'password' => $hashedPassword, 'created_at' => $created_at]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($username, $password)
    {
        try {
            $user = $this->getUserByUsername($username);
            if (!$user) {
                return 'Tên người dùng không tồn tại.';
            }
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                return 'success';
            } else {
                return 'Mật khẩu không đúng.';
            }
        } catch (PDOException $e) {
            return 'Có lỗi xảy ra, vui lòng thử lại sau.';
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
    }

    public function getUserById($userId)
    {
        try {
            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
}
