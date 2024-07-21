<?php
// products.php

// products.php

require_once 'db.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllProducts() {
        $sql = 'SELECT * FROM Products';
        $products = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            if (!empty($product['image_url'])) {
                $product['images'] = explode(',', $product['image_url']);
            } else {
                $product['images'] = [];
            }
        }

        return $products;
    }

    public function getProductById($id) {
        $sql = 'SELECT * FROM Products WHERE id = :id';
        $product = $this->db->query($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC);

        if ($product && !empty($product['image_urls'])) {
            $product['images'] = explode(',', $product['image_urls']);
        } else {
            $product['images'] = [];
        }

        return $product;
    }

    public function getProductReviews($productId) {
        $sql = 'SELECT * FROM Reviews WHERE product_id = :product_id';
        return $this->db->query($sql, ['product_id' => $productId])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsByCategoryId($categoryId) {
        $sql = 'SELECT * FROM Products WHERE category_id = :category_id';
        $products = $this->db->query($sql, ['category_id' => $categoryId])->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            if (!empty($product['image_urls'])) {
                $product['images'] = explode(',', $product['image_urls']);
            } else {
                $product['images'] = [];
            }
        }

        return $products;
    }

    public function getCategoryById($categoryId) {
        $sql = 'SELECT * FROM Categories WHERE id = :id';
        return $this->db->query($sql, ['id' => $categoryId])->fetch(PDO::FETCH_ASSOC);
    }

    public function getAverageRating($productId) {
        $sql = 'SELECT AVG(rating) as avg_rating FROM Reviews WHERE product_id = :product_id';
        $result = $this->db->query($sql, ['product_id' => $productId])->fetch(PDO::FETCH_ASSOC);
        return $result ? round($result['avg_rating'], 2) : 0;
    }

    public function getProductImage1($productId) {
        $sql = 'SELECT image_url FROM Products WHERE id = :product_id LIMIT 1';
        $result = $this->db->query($sql, ['product_id' => $productId])->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['image_url'];
        }
        return '';
    }

    public function getProductImage2($productName) {
        $sql = 'SELECT image_url FROM Products WHERE name = :name LIMIT 1 OFFSET 1';
        $result = $this->db->query($sql, ['name' => $productName])->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['image_url'];
        }
        return '';
    }

    public function getProductsByCategoryIdSorted($category_id, $sort) {
        $orderBy = 'v.view_count DESC'; // Default to popularity
        switch ($sort) {
            case 'alphabetical':
                $orderBy = 'p.name ASC';
                break;
            case 'price':
                $orderBy = 'p.price ASC';
                break;
        }

        $sql = "SELECT p.*, IFNULL(v.view_count, 0) as view_count
                FROM Products p
                LEFT JOIN (
                    SELECT product_id, COUNT(*) AS view_count
                    FROM ViewedProducts
                    GROUP BY product_id
                ) v ON p.id = v.product_id
                WHERE p.category_id = :category_id
                ORDER BY $orderBy";

        return $this->db->query($sql, ['category_id' => $category_id])->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>
