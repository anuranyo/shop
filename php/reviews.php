<?php
class Reviews {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getProductReviews($productId) {
        $sql = 'SELECT r.*, u.username AS user_name FROM Reviews r JOIN Users u ON r.user_id = u.id WHERE r.product_id = :product_id';
        return $this->db->query($sql, ['product_id' => $productId])->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
