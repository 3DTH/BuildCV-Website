<?php require_once 'app/views/layouts/admin.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chỉnh sửa người dùng</h2>
        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/admin/users/edit/<?= $user['id'] ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" class="form-control" name="full_name" 
                               value="<?= htmlspecialchars($user['full_name']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" 
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" 
                               value="<?= htmlspecialchars($user['phone']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" name="address" 
                               value="<?= htmlspecialchars($user['address']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vai trò</label>
                        <select class="form-select" name="role">
                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="status">
                            <option value="1" <?= $user['status'] ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= !$user['status'] ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>