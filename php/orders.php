<?php
// orders.php

require_once 'php/db.php';

class Order {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllOrders() {
        $sql = 'SELECT * FROM Orders';
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($id) {
        $sql = 'SELECT * FROM Orders WHERE id = :id';
        return $this->db->query($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC);
    }

    public function addOrder($user_id, $order_date, $status, $products) {
        // Get the highest current order_id and increment it by 1
        $sql = 'SELECT MAX(order_id) as max_order_id FROM Orders';
        $result = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        $order_id = $result['max_order_id'] + 1;

        foreach ($products as $product) {
            $sql = 'INSERT INTO Orders (order_id, user_id, product_id, quantity, order_date, status, total) 
                    VALUES (:order_id, :user_id, :product_id, :quantity, :order_date, :status, :total)';
            $this->db->query($sql, [
                'order_id' => $order_id,
                'user_id' => $user_id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'order_date' => $order_date,
                'status' => $status,
                'total' => $product['total']
            ]);
        }

        // Return the new order_id
        return $order_id;
    }

    public function getOrdersByUserId($userId) {
        $sql = 'SELECT o.order_id, o.product_id, p.name as product_name, p.image_url, o.total, o.quantity, o.order_date, o.status
                FROM Orders o
                JOIN Products p ON o.product_id = p.id
                WHERE o.user_id = :user_id
                ORDER BY o.order_id DESC, o.order_date';
        $params = ['user_id' => $userId];
        $orders = $this->db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);

        $groupedOrders = [];
        foreach ($orders as $order) {
            $groupedOrders[$order['order_id']][] = $order;
        }
        return $groupedOrders;
    }

    public function getPicWayByProductId($orderId) {
        $sql = 'SELECT p.image_url 
                FROM Orders o 
                JOIN Products p ON o.product_id = p.id 
                WHERE o.id = :order_id';
        $params = ['order_id' => $orderId];
        return $this->db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderByOrderId($orderId) {
        $sql = 'SELECT o.product_id, p.name, o.quantity, o.total
                FROM Orders o
                JOIN Products p ON o.product_id = p.id
                WHERE o.order_id = :order_id';
        $params = ['order_id' => $orderId];
        return $this->db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clearCart($userId) {
        $sql = 'DELETE FROM Cart WHERE user_id = :user_id';
        $params = ['user_id' => $userId];
        return $this->db->query($sql, $params);
    }
}
?>
