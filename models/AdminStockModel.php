<?php
class AdminStockModel {
    private $conn;

    // [QUAN TRỌNG] Phải có hàm này để kết nối Database
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // 1. Lấy danh sách sản phẩm theo Thương hiệu
    public function getProductsByBrand($brandId) {
        $sql = "SELECT id, name FROM products WHERE brand_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$brandId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy tất cả thương hiệu
    public function getAllBrands() {
        // Dòng này bị lỗi trước đó vì $this->conn chưa được khởi tạo
        return $this->conn->query("SELECT * FROM brands")->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Xử lý Nhập kho (Lưu lịch sử + Cộng dồn kho)
    public function importStock($productId, $size, $quantity, $importPrice) {
        try {
            $this->conn->beginTransaction();

            // A. Lưu vào bảng lịch sử nhập hàng (stock_imports)
            $sqlHist = "INSERT INTO stock_imports (product_id, size, quantity, import_price) VALUES (?, ?, ?, ?)";
            $stmtHist = $this->conn->prepare($sqlHist);
            $stmtHist->execute([$productId, $size, $quantity, $importPrice]);

            $sqlCheck = "SELECT stock FROM product_variants WHERE product_id = ? AND size = ?";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->execute([$productId, $size]);
            
            if ($stmtCheck->rowCount() > 0) {

                $sqlUpdate = "UPDATE product_variants SET stock = stock + ? WHERE product_id = ? AND size = ?";
                $this->conn->prepare($sqlUpdate)->execute([$quantity, $productId, $size]);
            } else {
 
                $sqlInsert = "INSERT INTO product_variants (product_id, size, stock) VALUES (?, ?, ?)";
                $this->conn->prepare($sqlInsert)->execute([$productId, $size, $quantity]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    public function getHistory() {

        $sql = "SELECT created_at, 
                       SUM(quantity) as total_qty, 
                       SUM(quantity * import_price) as total_money, 
                       COUNT(*) as items_count 
                FROM stock_imports 
                GROUP BY created_at 
                ORDER BY created_at DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function searchHistory($keyword) {
        $keyLike = "%$keyword%";
        $sql = "SELECT created_at, 
                       SUM(quantity) as total_qty, 
                       SUM(quantity * import_price) as total_money, 
                       COUNT(*) as items_count 
                FROM stock_imports 
                GROUP BY created_at 
                HAVING created_at LIKE ? 
                ORDER BY created_at DESC";

        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$keyLike]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistoryDetail($createdAt) {
        $sql = "SELECT s.*, p.name, p.image 
                FROM stock_imports s
                JOIN products p ON s.product_id = p.id
                WHERE s.created_at = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$createdAt]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllImportItems() {
        $sql = "SELECT s.*, p.name, b.name as brand_name
                FROM stock_imports s
                JOIN products p ON s.product_id = p.id
                LEFT JOIN brands b ON p.brand_id = b.id
                ORDER BY s.created_at DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>