<?php require_once 'app/views/layouts/admin.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết người dùng</h2>
        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="<?= $user['avatar'] ?? BASE_URL . '/assets/images/default-avatar.jpg' ?>" 
                         class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <h4><?= htmlspecialchars($user['full_name']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                    <p>
                        <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                        <span class="badge <?= $user['status'] ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $user['status'] ? 'Hoạt động' : 'Không hoạt động' ?>
                        </span>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Thông tin liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            <?= htmlspecialchars($user['phone']) ?>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <?= htmlspecialchars($user['address']) ?>
                        </li>
                        <li>
                            <i class="fas fa-calendar me-2"></i>
                            Tham gia: <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Gói dịch vụ đã đăng ký</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tên gói</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userPackages as $package): ?>
                            <tr>
                                <td><?= htmlspecialchars($package['name']) ?></td>
                                <td><?= date('d/m/Y', strtotime($package['start_date'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($package['end_date'])) ?></td>
                                <td>
                                    <span class="badge <?= strtotime($package['end_date']) > time() ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= strtotime($package['end_date']) > time() ? 'Đang hoạt động' : 'Hết hạn' ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>CV đã tạo</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Mẫu</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userCVs as $cv): ?>
                            <tr>
                                <td><?= htmlspecialchars($cv['title']) ?></td>
                                <td><?= htmlspecialchars($cv['template_name']) ?></td>
                                <td><?= date('d/m/Y', strtotime($cv['created_at'])) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/cv/preview/<?= $cv['id'] ?>" 
                                       class="btn btn-sm btn-info" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>