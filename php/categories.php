<?php
// categories.php

require_once 'db.php';

class Category {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllCategories() {
        $sql = 'SELECT * FROM Categories';
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id) {
        $sql = 'SELECT * FROM Categories WHERE id = :id';
        return $this->db->query($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC);
    }

    public function addCategory($name, $image_url) {
        $sql = 'INSERT INTO Categories (name, image_url) VALUES (:name, :image_url)';
        return $this->db->query($sql, ['name' => $name, 'image_url' => $image_url]);
    }

    public function updateCategory($id, $name, $image_url) {
        $sql = 'UPDATE Categories SET name = :name, image_url = :image_url WHERE id = :id';
        return $this->db->query($sql, ['id' => $id, 'name' => $name, 'image_url' => $image_url]);
    }

    public function deleteCategory($id) {
        $sql = 'DELETE FROM Categories WHERE id = :id';
        return $this->db->query($sql, ['id' => $id]);
    }
}
?>
