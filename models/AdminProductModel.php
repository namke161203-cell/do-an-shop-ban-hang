<?php
class AdminProductModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $sql = "SELECT p.*, c.name as cat_name, b.name as brand_name,
                GROUP_CONCAT(CONCAT(pv.size, ' (', pv.stock, ')') SEPARATOR ', ') as variant_info
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                LEFT JOIN product_variants pv ON p.id = pv.product_id
                GROUP BY p.id
                ORDER BY p.id ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchProducts($keyword) {
        $keyword = "%$keyword%";
        $sql = "SELECT p.*, c.name as cat_name, b.name as brand_name,
                GROUP_CONCAT(CONCAT(pv.size, ' (', pv.stock, ')') SEPARATOR ', ') as variant_info
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                LEFT JOIN product_variants pv ON p.id = pv.product_id
                WHERE p.name LIKE ? OR c.name LIKE ? OR b.name LIKE ?
                GROUP BY p.id
                ORDER BY p.id ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($name, $price, $old_price, $brand_id, $category_id, $image, $desc, $sale_price, $sale_start, $sale_end) {
  
        if ($sale_price > 0 && $sale_price >= $price) {
            echo "<script>alert('LỖI DỮ LIỆU: Giá Sale ($sale_price) không được lớn hơn hoặc bằng Giá bán ($price)!'); history.back();</script>";
            exit; // Dừng ngay lập tức, không cho lưu vào DB
        }


        $sql = "INSERT INTO products (name, price, old_price, brand_id, category_id, image, description, sale_price, sale_start, sale_end) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        
        $sale_start = !empty($sale_start) ? $sale_start : null;
        $sale_end = !empty($sale_end) ? $sale_end : null;

        $stmt->execute([$name, $price, $old_price, $brand_id, $category_id, $image, $desc, $sale_price, $sale_start, $sale_end]);
        return $this->conn->lastInsertId();
    }

    public function insertVariant($product_id, $size, $stock) {
        $sql = "INSERT INTO product_variants (product_id, size, stock) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$product_id, $size, $stock]);
    }

    public function addProductImage($product_id, $image_url) {
        $sql = "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$product_id, $image_url]);
    }

    public function getProductImages($product_id) {
        $stmt = $this->conn->prepare("SELECT * FROM product_images WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteProductImageById($image_id) {
        $stmt = $this->conn->prepare("DELETE FROM product_images WHERE id = ?");
        $stmt->execute([$image_id]);
    }

    public function deleteProductImages($product_id) {
        $stmt = $this->conn->prepare("DELETE FROM product_images WHERE product_id = ?");
        $stmt->execute([$product_id]);
    }

    public function delete($id) {
        $stmt1 = $this->conn->prepare("DELETE FROM product_variants WHERE product_id = ?");
        $stmt1->execute([$id]);

        $stmt2 = $this->conn->prepare("DELETE FROM product_images WHERE product_id = ?");
        $stmt2->execute([$id]);

        $stmt3 = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt3->execute([$id]);
    }

    // 5. Lấy thông tin 1 sản phẩm
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 6. Lấy danh sách Size
    public function getVariants($product_id) {
        $stmt = $this->conn->prepare("SELECT * FROM product_variants WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $price, $old_price, $brand_id, $category_id, $image, $desc, $sale_price, $sale_start, $sale_end) {

        if ($sale_price > 0 && $sale_price >= $price) {
            echo "<script>alert('LỖI DỮ LIỆU: Giá Sale ($sale_price) không được lớn hơn hoặc bằng Giá bán ($price)!'); history.back();</script>";
            exit; // Dừng ngay lập tức
        }


        $sql = "UPDATE products SET name=?, price=?, old_price=?, brand_id=?, category_id=?, image=?, description=?, sale_price=?, sale_start=?, sale_end=? WHERE id=?";
        
        $stmt = $this->conn->prepare($sql);
        
        $sale_start = !empty($sale_start) ? $sale_start : null;
        $sale_end = !empty($sale_end) ? $sale_end : null;

        $stmt->execute([$name, $price, $old_price, $brand_id, $category_id, $image, $desc, $sale_price, $sale_start, $sale_end, $id]);
    }

    public function deleteVariants($product_id) {
        $stmt = $this->conn->prepare("DELETE FROM product_variants WHERE product_id = ?");
        $stmt->execute([$product_id]);
    }


    public function getBrands() { return $this->conn->query("SELECT * FROM brands")->fetchAll(); }
    public function getCategories() { return $this->conn->query("SELECT * FROM categories")->fetchAll(); }
}
?>