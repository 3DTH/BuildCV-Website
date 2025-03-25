<?php
class FileHelper {
    public static function upload($file, $destination, $allowed_types = ['jpg', 'jpeg', 'png', 'gif'], $max_size = 2097152) {
        // Kiểm tra lỗi
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Kiểm tra kích thước
        if ($file['size'] > $max_size) {
            return false;
        }

        // Kiểm tra loại file
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_types)) {
            return false;
        }

        // Tạo tên file mới để tránh trùng lặp
        $new_filename = uniqid() . '.' . $file_extension;
        $upload_path = $destination . '/' . $new_filename;

        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        // Di chuyển file upload
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            return $new_filename;
        }

        return false;
    }
}