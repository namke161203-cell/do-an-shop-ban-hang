<?php

require_once 'config/db.php';

class AdminUserModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAllUsers($currentUserId) {
        $sql = "SELECT * FROM users WHERE id != ? ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$currentUserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {

        $sql = "UPDATE users SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$status, $id]);
    }

    public function searchUsers($keyword, $currentUserId) {
        $keyword = "%$keyword%";
        $sql = "SELECT * FROM users WHERE id != ? AND (fullname LIKE ? OR email LIKE ? OR phone LIKE ?) ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$currentUserId, $keyword, $keyword, $keyword]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateRole($id, $role) {
        try {
            $sql = "UPDATE users SET role = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$role, $id]);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>