-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS webbuildcv;
USE webbuildcv;

-- Bảng người dùng
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    full_name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    profile_picture VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng mẫu CV
CREATE TABLE templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    thumbnail VARCHAR(255),
    css_file VARCHAR(255),
    is_premium BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng CV
CREATE TABLE cvs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    template_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    summary TEXT,
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES templates(id)
);

-- Bảng học vấn
CREATE TABLE education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    institution VARCHAR(100) NOT NULL,
    degree VARCHAR(100),
    field_of_study VARCHAR(100),
    start_date DATE,
    end_date DATE,
    description TEXT,
    order_index INT DEFAULT 0,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE
);

-- Bảng kinh nghiệm làm việc
CREATE TABLE experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    company VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    start_date DATE,
    end_date DATE,
    is_current BOOLEAN DEFAULT FALSE,
    description TEXT,
    order_index INT DEFAULT 0,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE
);

-- Bảng kỹ năng
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    level INT DEFAULT 0,
    order_index INT DEFAULT 0,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE
);

-- Bảng ngôn ngữ
CREATE TABLE languages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    proficiency VARCHAR(50),
    order_index INT DEFAULT 0,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE
);

-- Bảng chứng chỉ
CREATE TABLE certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    issuer VARCHAR(100),
    issue_date DATE,
    expiry_date DATE,
    description TEXT,
    order_index INT DEFAULT 0,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE
);

-- Bảng dự án
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    role VARCHAR(100),
    start_date DATE,
    end_date DATE,
    description TEXT,
    url VARCHAR(255),
    order_index INT DEFAULT 0,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE
);

-- Bảng thông tin liên hệ
CREATE TABLE contact_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    type VARCHAR(50) NOT NULL, -- 'website', 'github', 'linkedin', 'facebook', 'twitter', etc.
    value VARCHAR(255) NOT NULL, -- URL hoặc liên hệ trực tiếp
    is_primary BOOLEAN DEFAULT FALSE, -- Đánh dấu thông tin liên hệ chính
    order_index INT DEFAULT 0, -- Sắp xếp thứ tự hiển thị
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE
);

-- Bảng gói dịch vụ
CREATE TABLE packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    export_limit INT NULL COMMENT 'NULL có nghĩa là không giới hạn',
    duration_days INT DEFAULT 30 COMMENT 'Thời hạn gói tính bằng ngày',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng gói dịch vụ của người dùng
CREATE TABLE user_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    package_id INT NOT NULL,
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_date TIMESTAMP NULL,
    remaining_exports INT NULL COMMENT 'NULL có nghĩa là không giới hạn',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES packages(id)
);

-- Bảng lịch sử xuất CV
CREATE TABLE export_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    cv_id INT NOT NULL,
    package_id INT,
    format VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES user_packages(id)
);

-- Thêm các gói dịch vụ mẫu
INSERT INTO packages (name, description, price, export_limit, duration_days) VALUES
('Gói Cơ Bản', 'Xuất tối đa 50 lần CV sang PDF', 5000, 50, 30),
('Gói Nâng Cao', 'Xuất tối đa 1000 lần CV sang PDF', 10000, 1000, 30),
('Gói Không Giới Hạn', 'Xuất không giới hạn CV sang PDF', 20000, NULL, 30);