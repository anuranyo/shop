<?php
class Cart {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getCartItemsByUserId($userId) {
        $sql = 'SELECT ca.product_id, p.name, p.price, p.image_url, ca.quantity, c.name as category_name
                FROM Cart ca
                JOIN Products p ON ca.product_id = p.id
                JOIN Categories c ON p.category_id = c.id
                WHERE ca.user_id = :user_id';
        return $this->db->query($sql, ['user_id' => $userId])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCartItemsId($userId) {
        $sql = 'SELECT product_id
                FROM Cart
                WHERE user_id = :user_id';
        return $this->db->query($sql, ['user_id' => $userId])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeItem($userId, $productId) {
        $sql = 'DELETE FROM Cart WHERE user_id = :user_id AND product_id = :product_id';
        $this->db->query($sql, [
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }

    public function addToCart($userId, $productId, $quantity = 1) {
        // Check if the product already exists in the cart
        $sql = 'SELECT * FROM Cart WHERE user_id = :user_id AND product_id = :product_id';
        $params = [
            'user_id' => $userId,
            'product_id' => $productId
        ];
        $existingItem = $this->db->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    
        if ($existingItem) {
            // If the product exists, update the quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            $sql = 'UPDATE Cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id';
            $params['quantity'] = $newQuantity;
        } else {
            // If the product does not exist, insert a new record
            $sql = 'INSERT INTO Cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)';
            $params['quantity'] = $quantity;
        }
    
        return $this->db->query($sql, $params);
    }
    
    public function updateQuantity($userId, $productId, $quantityChange) {
        $sql = 'UPDATE Cart SET quantity = quantity + :quantityChange WHERE user_id = :user_id AND product_id = :product_id';
        $this->db->query($sql, [
            'quantityChange' => $quantityChange,
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }

    public function getTotalPrice($userId) {
        $sql = 'SELECT SUM(p.price * ca.quantity) as total_price
                FROM Cart ca
                JOIN Products p ON ca.product_id = p.id
                WHERE ca.user_id = :user_id';
        return $this->db->query($sql, ['user_id' => $userId])->fetchColumn();
    }

    public function removeAllItems($userId) {
        $sql = 'DELETE FROM Cart WHERE user_id = :user_id';
        $this->db->query($sql, ['user_id' => $userId]);
    }
}
?>
