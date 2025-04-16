<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard - BuildCV' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h3>BuildCV Admin</h3>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-item">
                    <a href="<?= BASE_URL ?>/admin" class="menu-link">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= BASE_URL ?>/admin/users" class="menu-link">
                        <i class="fas fa-users"></i> Quản lý người dùng
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= BASE_URL ?>/admin/packages" class="menu-link">
                        <i class="fas fa-box"></i> Quản lý gói dịch vụ
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= BASE_URL ?>/admin/templates" class="menu-link">
                        <i class="fas fa-file-alt"></i> Quản lý mẫu CV
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Navbar -->
            <nav class="admin-navbar">
                <div class="navbar-left">
                    <button class="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div class="navbar-right">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> Admin
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/profile">Hồ sơ</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/auth/logout">Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="admin-content">
                <?php require_once $viewPath; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/admin.js"></script>
</body>
</html>