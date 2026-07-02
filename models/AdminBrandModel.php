<?php
class AdminBrandModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM brands ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchBrands($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare("SELECT * FROM brands WHERE name LIKE ? ORDER BY id ASC");
        $stmt->execute([$keyword]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($name) {
        $stmt = $this->conn->prepare("INSERT INTO brands (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function delete($id) {

        
        $stmt = $this->conn->prepare("DELETE FROM brands WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM brands WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name) {
        $stmt = $this->conn->prepare("UPDATE brands SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    public function getProductsByBrandId($brandId) {
        $sql = "SELECT * FROM products WHERE brand_id = ? ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$brandId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>