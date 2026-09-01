<?php
require_once __DIR__ . '/../config/database.php';

class ProductRepository {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(): array {
        $stmt = $this->db->query('SELECT * FROM products WHERE is_active = 1 ORDER BY id ASC');
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = ? AND is_active = 1');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function search(string $query): array {
        $q    = '%' . $query . '%';
        $stmt = $this->db->prepare(
            'SELECT * FROM products
             WHERE is_active = 1
               AND (name LIKE ? OR category LIKE ? OR description LIKE ?)
             ORDER BY name ASC'
        );
        $stmt->execute([$q, $q, $q]);
        return $stmt->fetchAll();
    }

    public function getByCategory(string $category): array {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE category = ? AND is_active = 1 ORDER BY id ASC');
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }
}
