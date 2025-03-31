<?php
class FileUploader {
    private $uploadDir;
    private $allowedTypes;
    private $maxSize;

    public function __construct() {
        $this->uploadDir = ROOT_PATH . '/uploads/profile_pictures/';
        $this->allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $this->maxSize = 5 * 1024 * 1024; // 5MB
    }

    public function uploadProfilePicture($file) {
        // Check file size
        if ($file['size'] > $this->maxSize) {
            return [
                'success' => false,
                'error' => 'File không được vượt quá 5MB'
            ];
        }

        // Check file type
        if (!in_array($file['type'], $this->allowedTypes)) {
            return [
                'success' => false,
                'error' => 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF)'
            ];
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $destination = $this->uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'filename' => $filename
            ];
        }

        return [
            'success' => false,
            'error' => 'Có lỗi xảy ra khi tải file lên'
        ];
    }
}