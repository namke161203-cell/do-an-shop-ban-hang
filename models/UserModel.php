<?php
class UserModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // 1. Kiểm tra email đã tồn tại chưa
    // (Hàm này dùng chung cho cả Đăng ký và Quên mật khẩu)
    public function checkEmail($email) {
        $stmt = $this->conn->prepare("SELECT id, email, fullname FROM users WHERE email = ?");
        $stmt->execute([$email]);
        // Trả về dữ liệu user nếu tìm thấy, ngược lại trả về false
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Đăng ký tài khoản mới
    public function register($fullname, $email, $password, $phone, $address) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (fullname, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, 'customer')";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$fullname, $email, $hashed_password, $phone, $address]);
    }

    // 3. Đăng nhập
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // 4. Lấy thông tin chi tiết user theo ID
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT id, fullname, email, phone, address, role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. Cập nhật thông tin cá nhân
    public function updateProfile($id, $fullname, $phone, $address) {
        $sql = "UPDATE users SET fullname = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$fullname, $phone, $address, $id]);
    }

    // ================================================================
    // CÁC HÀM MỚI CHO CHỨC NĂNG QUÊN MẬT KHẨU
    // ================================================================

    // 6. Lưu Token reset password vào bảng password_resets
    public function saveResetToken($email, $token) {
        // Xóa token cũ của email này nếu có (để tránh rác database)
        $sqlDelete = "DELETE FROM password_resets WHERE email = ?";
        $this->conn->prepare($sqlDelete)->execute([$email]);

        // Thêm token mới
        $sql = "INSERT INTO password_resets (email, token) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$email, $token]);
    }

    // 7. Kiểm tra Token có hợp lệ không (Đúng token và chưa hết hạn)
    public function verifyToken($email, $token) {
        $sql = "SELECT * FROM password_resets WHERE email = ? AND token = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email, $token]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Kiểm tra thời gian hết hạn (ví dụ: token chỉ sống trong 1 giờ = 3600 giây)
            $createdAt = strtotime($result['created_at']);
            if (time() - $createdAt < 3600) { 
                return true; // Token hợp lệ
            }
        }
        return false; // Token sai hoặc hết hạn
    }

    // 8. Cập nhật mật khẩu mới theo Email
    public function updatePasswordByEmail($email, $newPassword) {
        // Mã hóa mật khẩu mới
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Cập nhật bảng users
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $this->conn->prepare($sql)->execute([$hashed, $email]);

        // Xóa token trong bảng password_resets sau khi đổi thành công
        $this->conn->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
    }
}
?>