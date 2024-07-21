<?php
// comments.php

require_once 'php/db.php';

class Comment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllComments() {
        $sql = 'SELECT * FROM Comments';
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommentById($id) {
        $sql = 'SELECT * FROM Comments WHERE id = :id';
        return $this->db->query($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC);
    }

    public function getCommentsByProductId($product_id) {
        $sql = 'SELECT * FROM Comments WHERE product_id = :product_id';
        return $this->db->query($sql, ['product_id' => $product_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addComment($product_id, $user_id, $comment, $rating, $comment_date) {
        $sql = 'INSERT INTO Comments (product_id, user_id, comment, rating, comment_date) VALUES (:product_id, :user_id, :comment, :rating, :comment_date)';
        return $this->db->query($sql, [
            'product_id' => $product_id,
            'user_id' => $user_id,
            'comment' => $comment,
            'rating' => $rating,
            'comment_date' => $comment_date
        ]);
    }

    public function updateComment($id, $comment, $rating) {
        $sql = 'UPDATE Comments SET comment = :comment, rating = :rating WHERE id = :id';
        return $this->db->query($sql, [
            'id' => $id,
            'comment' => $comment,
            'rating' => $rating
        ]);
    }

    public function deleteComment($id) {
        $sql = 'DELETE FROM Comments WHERE id = :id';
        return $this->db->query($sql, ['id' => $id]);
    }
}
?>
