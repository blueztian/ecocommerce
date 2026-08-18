<?php
require_once __DIR__ . '/../config/database.php';

class ProductRepository {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(): array {
        $stmt = $this->db->query('SELECT * FROM products ORDER BY id ASC');
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
