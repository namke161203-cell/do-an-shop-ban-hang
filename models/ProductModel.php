<?php
class ProductModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // =================================================================
    // PHẦN 1: CÁC HÀM LẤY DỮ LIỆU (READ) - Dùng cho trang hiển thị
    // =================================================================

    // 1. Lấy danh sách sản phẩm (Lọc, Tìm kiếm, Phân trang)
    public function getProducts($keyword = '', $categoryId = null, $sort = 'newest', $limit = 9, $offset = 0) {
        $sql = "SELECT p.*, c.name as cat_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";
        
        $params = [];

        // Tìm kiếm theo tên
        if (!empty($keyword)) {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%$keyword%";
        }

        // Lọc theo danh mục
        if (!empty($categoryId)) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        // Sắp xếp
        switch ($sort) {
            case 'price_asc': $sql .= " ORDER BY p.price ASC"; break;
            case 'price_desc': $sql .= " ORDER BY p.price DESC"; break;
            case 'name_az': $sql .= " ORDER BY p.name ASC"; break;
            default: $sql .= " ORDER BY p.created_at DESC"; break;
        }

        // Phân trang
        $sql .= " LIMIT $offset, $limit";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Đếm tổng số lượng (Để tính số trang)
    public function countTotal($keyword = '', $categoryId = null) {
        $sql = "SELECT COUNT(*) as total FROM products WHERE 1=1";
        $params = [];
        if (!empty($keyword)) { $sql .= " AND name LIKE ?"; $params[] = "%$keyword%"; }
        if (!empty($categoryId)) { $sql .= " AND category_id = ?"; $params[] = $categoryId; }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 3. Lấy chi tiết 1 sản phẩm + Size (Variants)
    public function getProductDetail($id) {
        // Lấy thông tin chung + Tên thương hiệu
        $stmt = $this->conn->prepare("SELECT p.*, b.name as brand_name FROM products p LEFT JOIN brands b ON p.brand_id = b.id WHERE p.id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Lấy thêm các size còn hàng
            $stmtVars = $this->conn->prepare("SELECT * FROM product_variants WHERE product_id = ? AND stock > 0 ORDER BY size ASC");
            $stmtVars->execute([$id]);
            $product['variants'] = $stmtVars->fetchAll(PDO::FETCH_ASSOC);
        }
        return $product;
    }

    // 4. Lấy 4 sản phẩm cùng danh mục (Gợi ý)
    public function getRelatedProducts($categoryId, $currentProductId) {
        $sql = "SELECT * FROM products 
                WHERE category_id = ? AND id != ? 
                ORDER BY created_at DESC 
                LIMIT 4";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$categoryId, $currentProductId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5. Lấy danh sách ảnh phụ (Gallery)
    public function getProductImages($productId) {
        $sql = "SELECT * FROM product_images WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5b. [MỚI] Tăng lượt xem sản phẩm
    public function incrementView($id) {
        $sql = "UPDATE products SET views = views + 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // =================================================================
    // PHẦN 2: CÁC HÀM THÊM/SỬA/XÓA (WRITE) - Dùng cho Admin
    // =================================================================

    // 6. [MỚI] Thêm ảnh phụ vào Gallery
    public function addProductImage($productId, $imageUrl) {
        $sql = "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$productId, $imageUrl]);
    }

    // 7. [MỚI] Thêm sản phẩm mới (Cần thiết cho AdminController store)
    public function createProduct($name, $price, $old_price, $image, $description, $categoryId, $brandId = null) {
        $sql = "INSERT INTO products (name, price, old_price, image, description, category_id, brand_id, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([$name, $price, $old_price, $image, $description, $categoryId, $brandId]);
        
        if ($result) {
            // [QUAN TRỌNG] Trả về ID của sản phẩm vừa tạo để dùng cho upload ảnh gallery
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // 8. [MỚI] Xóa ảnh phụ (Dùng khi xóa sản phẩm hoặc sửa sản phẩm)
   public function deleteProductImageById($imageId) {
    $sql = "DELETE FROM product_images WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$imageId]);
    }
    public function getFlashSaleProducts() {
        // Set múi giờ Việt Nam để so sánh chính xác
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $now = date('Y-m-d H:i:s');

        $sql = "SELECT p.*, c.name as cat_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.sale_price > 0 
                AND p.sale_start <= '$now' 
                AND p.sale_end >= '$now'
                ORDER BY p.sale_end ASC 
                LIMIT 4";
        
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>