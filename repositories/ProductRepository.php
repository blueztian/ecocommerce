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

    public function search(string $query): array {
        $q    = '%' . $query . '%';
        $stmt = $this->db->prepare(
            'SELECT * FROM products
             WHERE name LIKE ? OR category LIKE ? OR description LIKE ?
             ORDER BY name ASC'
        );
        $stmt->execute([$q, $q, $q]);
        return $stmt->fetchAll();
    }

    public function getByCategory(string $category): array {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE category = ? ORDER BY id ASC');
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }
}
