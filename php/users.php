<?php
// users.php

require_once 'db.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllUsers() {
        $sql = 'SELECT * FROM Users';
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($userId) {
        $sql = 'SELECT * FROM Users WHERE id = :id';
        return $this->db->query($sql, ['id' => $userId])->fetch(PDO::FETCH_ASSOC);
    }

    public function isAdmin($userId) {
        $sql = 'SELECT role FROM Users WHERE id = :id';
        $result = $this->db->query($sql, ['id' => $userId])->fetch(PDO::FETCH_ASSOC);
        return $result && $result['role'] === 'admin';
    }

    public function addUser($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO Users (username, email, password) VALUES (:username, :email, :password)';
        return $this->db->query($sql, [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);
    }

    public function authenticateUser($email, $password) {
        $sql = 'SELECT * FROM Users WHERE email = :email';
        $user = $this->db->query($sql, ['email' => $email])->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function deleteUser($id) {
        $sql = 'DELETE FROM Users WHERE id = :id';
        return $this->db->query($sql, ['id' => $id]);
    }

}
?>
