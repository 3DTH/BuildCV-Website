<?php
$title = 'Chỉnh sửa CV - ' . htmlspecialchars($cv['title']);
require_once 'app/views/layouts/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Thông tin cơ bản</h5>
                    <form action="<?= BASE_URL ?>/cv/edit/<?= $cv['id'] ?>" method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề CV</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="<?= htmlspecialchars($cv['title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">Tóm tắt</label>
                            <textarea class="form-control" id="summary" name="summary"
                                rows="4"><?= htmlspecialchars($cv['summary']) ?></textarea>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_public"
                                name="is_public" <?= $cv['is_public'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_public">Công khai CV</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Lưu thay đổi</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Các phần CV</h5>
                    <div class="list-group">
                        <a href="#personal" class="list-group-item list-group-item-action">
                            <i class="fas fa-user"></i> Thông tin cá nhân
                        </a>
                        <a href="#education" class="list-group-item list-group-item-action">
                            <i class="fas fa-graduation-cap"></i> Học vấn
                        </a>
                        <a href="#experience" class="list-group-item list-group-item-action">
                            <i class="fas fa-briefcase"></i> Kinh nghiệm
                        </a>
                        <a href="#skills" class="list-group-item list-group-item-action">
                            <i class="fas fa-tools"></i> Kỹ năng
                        </a>
                        <a href="#languages" class="list-group-item list-group-item-action">
                            <i class="fas fa-language"></i> Ngoại ngữ
                        </a>
                        <a href="#certificates" class="list-group-item list-group-item-action">
                            <i class="fas fa-certificate"></i> Chứng chỉ
                        </a>
                        <a href="#projects" class="list-group-item list-group-item-action">
                            <i class="fas fa-project-diagram"></i> Dự án
                        </a>
                        <a href="#contacts" class="list-group-item list-group-item-action">
                            <i class="fas fa-address-card"></i> Thông tin liên hệ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9">
            <!-- Alerts -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Personal Information Section -->
            <div class="card shadow-sm mb-4" id="personal">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <form id="personalForm" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" name="full_name" 
                                    value="<?= htmlspecialchars($user['full_name']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" 
                                    value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" name="phone" 
                                    value="<?= htmlspecialchars($user['phone']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="address" 
                                    value="<?= htmlspecialchars($user['address']) ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                    </form>
                </div>
            </div>

            <!-- Education Section -->
            <div class="card shadow-sm mb-4" id="education">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Học vấn</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#educationModal">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="educationList">
                        <?php foreach ($education as $edu): ?>
                            <div class="education-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1"><?= htmlspecialchars($edu['institution']) ?></h6>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editEducation(<?= $edu['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteEducation(<?= $edu['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <p class="mb-1"><?= htmlspecialchars($edu['degree']) ?> - 
                                   <?= htmlspecialchars($edu['field_of_study']) ?></p>
                                <p class="text-muted small mb-1">
                                    <?= date('m/Y', strtotime($edu['start_date'])) ?> - 
                                    <?= $edu['end_date'] ? date('m/Y', strtotime($edu['end_date'])) : 'Hiện tại' ?>
                                </p>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($edu['description'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Experience Section -->
            <div class="card shadow-sm mb-4" id="experience">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Kinh nghiệm làm việc</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#experienceModal">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="experienceList">
                        <?php foreach ($experiences as $exp): ?>
                            <div class="experience-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1"><?= htmlspecialchars($exp['position']) ?></h6>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editExperience(<?= $exp['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteExperience(<?= $exp['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <p class="mb-1"><?= htmlspecialchars($exp['company']) ?></p>
                                <p class="text-muted small mb-1">
                                    <?= date('m/Y', strtotime($exp['start_date'])) ?> - 
                                    <?= $exp['is_current'] ? 'Hiện tại' : date('m/Y', strtotime($exp['end_date'])) ?>
                                </p>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($exp['description'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Skills Section -->
            <div class="card shadow-sm mb-4" id="skills">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Kỹ năng</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#skillModal">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="skillsList">
                        <?php foreach ($skills as $skill): ?>
                            <div class="skill-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-1"><?= htmlspecialchars($skill['name']) ?></h6>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editSkill(<?= $skill['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteSkill(<?= $skill['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="progress mt-2" style="height: 10px;">
                                    <div class="progress-bar" role="progressbar" 
                                        style="width: <?= $skill['level'] ?>%"
                                        aria-valuenow="<?= $skill['level'] ?>" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Languages Section -->
            <div class="card shadow-sm mb-4" id="languages">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-language me-2"></i>Ngoại ngữ</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#languageModal">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="languagesList">
                        <?php foreach ($languages as $lang): ?>
                            <div class="language-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-1"><?= htmlspecialchars($lang['name']) ?></h6>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editLanguage(<?= $lang['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteLanguage(<?= $lang['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <p class="mb-0"><?= htmlspecialchars($lang['proficiency']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Certificates Section -->
            <div class="card shadow-sm mb-4" id="certificates">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>Chứng chỉ</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#certificateModal">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="certificatesList">
                        <?php foreach ($certificates as $cert): ?>
                            <div class="certificate-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1"><?= htmlspecialchars($cert['name']) ?></h6>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editCertificate(<?= $cert['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteCertificate(<?= $cert['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <p class="mb-1"><?= htmlspecialchars($cert['issuer']) ?></p>
                                <p class="text-muted small mb-1">
                                    <?= date('m/Y', strtotime($cert['issue_date'])) ?>
                                    <?php if ($cert['expiry_date']): ?> 
                                        - <?= date('m/Y', strtotime($cert['expiry_date'])) ?>
                                    <?php endif; ?>
                                </p>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($cert['description'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Projects Section -->
            <div class="card shadow-sm mb-4" id="projects">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-project-diagram me-2"></i>Dự án</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#projectModal">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="projectsList">
                        <?php foreach ($projects as $project): ?>
                            <div class="project-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1"><?= htmlspecialchars($project['title']) ?></h6>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editProject(<?= $project['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteProject(<?= $project['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <p class="mb-1"><?= htmlspecialchars($project['role']) ?></p>
                                <p class="text-muted small mb-1">
                                    <?= date('m/Y', strtotime($project['start_date'])) ?> - 
                                    <?= $project['end_date'] ? date('m/Y', strtotime($project['end_date'])) : 'Hiện tại' ?>
                                </p>
                                <p class="mb-2"><?= nl2br(htmlspecialchars($project['description'])) ?></p>
                                <?php if ($project['url']): ?>
                                    <a href="<?= htmlspecialchars($project['url']) ?>" target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Xem dự án
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="card shadow-sm mb-4" id="contacts">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-address-card me-2"></i>Thông tin liên hệ</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="contactsList">
                        <?php foreach ($contacts as $contact): ?>
                            <div class="contact-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-<?= getContactIcon($contact['type']) ?> me-2"></i>
                                        <?= htmlspecialchars($contact['value']) ?>
                                        <?php if ($contact['is_primary']): ?>
                                            <span class="badge bg-primary ms-2">Chính</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editContact(<?= $contact['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteContact(<?= $contact['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Helper function for contact icons
function getContactIcon($type) {
    $icons = [
        'website' => 'globe',
        'github' => 'github',
        'linkedin' => 'linkedin',
        'facebook' => 'facebook',
        'twitter' => 'twitter',
        'email' => 'envelope',
        'phone' => 'phone',
        'default' => 'link'
    ];
    return $icons[$type] ?? $icons['default'];
}
?>

<?php require_once 'app/views/layouts/footer.php'; ?>