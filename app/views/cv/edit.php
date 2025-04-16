<?php
$title = 'Chỉnh sửa CV - ' . htmlspecialchars($cv['title']);
require_once 'app/views/layouts/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Thêm nút xem trước vào phần sidebar -->
        <div class="card shadow-sm mb-4 ">
            <div class="card-body">
                <button type="button" class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#previewModal">
                    <i class="fas fa-eye me-2"></i>Xem trước CV
                </button>
                <button type="button" class="btn btn-outline-primary w-100" onclick="printCV()">
                    <i class="fas fa-print me-2"></i>In CV
                </button>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Thông tin cơ bản</h5>
                    <!-- Separate form for CV information -->
                    <form action="<?= BASE_URL ?>/cv/updateBasicInfo/<?= $cv['id'] ?>" method="POST">
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
                    <form id="personalForm" method="POST" action="<?= BASE_URL ?>/cv/updatePersonalInfo">
                        <input type="hidden" name="cv_id" value="<?= $cv['id'] ?>">
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

            <!-- Education Modal -->
            <div class="modal fade" id="educationModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm học vấn</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="educationForm">
                                <input type="hidden" name="cv_id" value="<?= $cv['id'] ?>">
                                <input type="hidden" name="id" value="">
                                <div class="mb-3">
                                    <label class="form-label">Trường học</label>
                                    <input type="text" class="form-control" name="institution" value="Đại học Công nghệ Thông tin" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bằng cấp</label>
                                    <input type="text" class="form-control" name="degree" value="Cử nhân" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngành học</label>
                                    <input type="text" class="form-control" name="field_of_study" value="CNTT" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày bắt đầu</label>
                                    <input type="date" class="form-control" name="start_date" value="2022-06-28" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày kết thúc</label>
                                    <input type="date" class="form-control" name="end_date" value="2025-06-28">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" onclick="submitEducation()">Lưu</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Education Section -->
            <div class="card shadow-sm mb-4" id="education">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Học vấn</h5>
                        <!-- Nút để mở Modal -->
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#educationModal">
                            <i class="fas fa-plus"></i> Thêm
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Danh sách học vấn -->
                    <div id="educationList">
                        <?php foreach ($education as $edu): ?>
                            <div class="education-item border-bottom pb-3 mb-3" data-id="<?= $edu['id'] ?>">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($edu['institution']) ?></h6>
                                        <p class="mb-1"><?= htmlspecialchars($edu['degree']) ?> - <?= htmlspecialchars($edu['field_of_study']) ?></p>
                                        <p class="text-muted small mb-1">
                                            <?= date('m/Y', strtotime($edu['start_date'])) ?> -
                                            <?= $edu['end_date'] ? date('m/Y', strtotime($edu['end_date'])) : 'Hiện tại' ?>
                                        </p>
                                        <?php if ($edu['description']): ?>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($edu['description'])) ?></p>
                                        <?php endif; ?>
                                    </div>
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Experience Modal -->
            <div class="modal fade" id="experienceModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm kinh nghiệm làm việc</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="experienceForm">
                                <input type="hidden" name="cv_id" value="<?= $cv['id'] ?>">
                                <input type="hidden" name="id" value="">
                                <div class="mb-3">
                                    <label class="form-label">Vị trí</label>
                                    <input type="text" class="form-control" name="position" value="Backend Developer" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Công ty</label>
                                    <input type="text" class="form-control" name="company" value="FPT" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày bắt đầu</label>
                                    <input type="date" class="form-control" name="start_date" value="2024-06-28" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày kết thúc</label>
                                    <input type="date" class="form-control" name="end_date" value="2024-09-28">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" name="is_current" id="is_current">
                                    <label class="form-check-label" for="is_current">Công việc hiện tại</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mô tả công việc</label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" onclick="submitExperience()">Lưu</button>
                        </div>
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
                        <?php foreach ($experience as $exp): ?>
                            <div class="experience-item border-bottom pb-3 mb-3" 
                                data-id="<?= $exp['id'] ?>"
                                data-start-date="<?= $exp['start_date'] ?>"
                                data-end-date="<?= $exp['end_date'] ?>"
                                data-is-current="<?= $exp['is_current'] ? 'true' : 'false' ?>">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($exp['position']) ?></h6>
                                        <p class="mb-1"><?= htmlspecialchars($exp['company']) ?></p>
                                        <p class="text-muted small mb-1">
                                            <?= date('m/Y', strtotime($exp['start_date'])) ?> -
                                            <?= $exp['is_current'] ? 'Hiện tại' : date('m/Y', strtotime($exp['end_date'])) ?>
                                        </p>
                                        <?php if ($exp['description']): ?>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($exp['description'])) ?></p>
                                        <?php endif; ?>
                                    </div>
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Skills Modal -->
            <div class="modal fade" id="skillModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm kỹ năng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="skillForm">
                                <input type="hidden" name="cv_id" value="<?= $cv['id'] ?>">
                                <input type="hidden" name="id" value="">
                                <div class="mb-3">
                                    <label class="form-label">Tên kỹ năng</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mức độ thành thạo (<span id="levelValue">50%</span>)</label>
                                    <input type="range" class="form-range" name="level" min="0" max="100" value="50">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" onclick="submitSkill()">Lưu</button>
                        </div>
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
                            <div class="skill-item border-bottom pb-3 mb-3" data-id="<?= $skill['id'] ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1 me-3">
                                        <h6 class="mb-1"><?= htmlspecialchars($skill['name']) ?></h6>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: <?= $skill['level'] ?>%"
                                                aria-valuenow="<?= $skill['level'] ?>"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted"><?= $skill['level'] ?>%</small>
                                    </div>
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Languages Modal -->
            <div class="modal fade" id="languageModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm ngoại ngữ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="languageForm">
                                <input type="hidden" name="cv_id" value="<?= $cv['id'] ?>">
                                <input type="hidden" name="id" value="">
                                <div class="mb-3">
                                    <label class="form-label">Ngoại ngữ</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Trình độ</label>
                                    <select class="form-select" name="proficiency" required>
                                        <option value="">Chọn trình độ</option>
                                        <option value="Sơ cấp">Sơ cấp</option>
                                        <option value="Trung cấp">Trung cấp</option>
                                        <option value="Cao cấp">Cao cấp</option>
                                        <option value="Bản ngữ">Bản ngữ</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" onclick="submitLanguage()">Lưu</button>
                        </div>
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
                            <div class="language-item border-bottom pb-3 mb-3" data-id="<?= $lang['id'] ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($lang['name']) ?></h6>
                                        <p class="mb-0"><?= htmlspecialchars($lang['proficiency']) ?></p>
                                    </div>
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Certificates Modal -->
            <div class="modal fade" id="certificateModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm chứng chỉ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="certificateForm">
                                <input type="hidden" name="cv_id" value="<?= $cv['id'] ?>">
                                <input type="hidden" name="id" value="">
                                <div class="mb-3">
                                    <label class="form-label">Tên chứng chỉ</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tổ chức cấp</label>
                                    <input type="text" class="form-control" name="issuer" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày cấp</label>
                                    <input type="date" class="form-control" name="issue_date" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày hết hạn</label>
                                    <input type="date" class="form-control" name="expiry_date">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" onclick="submitCertificate()">Lưu</button>
                        </div>
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
                            <div class="certificate-item border-bottom pb-3 mb-3" data-id="<?= $cert['id'] ?>">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($cert['name']) ?></h6>
                                        <p class="mb-1"><?= htmlspecialchars($cert['issuer']) ?></p>
                                        <p class="text-muted small mb-1">
                                            <?= date('m/Y', strtotime($cert['issue_date'])) ?>
                                            <?= $cert['expiry_date'] ? ' - ' . date('m/Y', strtotime($cert['expiry_date'])) : '' ?>
                                        </p>
                                        <?php if ($cert['description']): ?>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($cert['description'])) ?></p>
                                        <?php endif; ?>
                                    </div>
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Projects Modal -->
            <div class="modal fade" id="projectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm dự án</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="projectForm">
                                <input type="hidden" name="cv_id" value="<?= $cv['id'] ?>">
                                <input type="hidden" name="id" value="">
                                <div class="mb-3">
                                    <label class="form-label">Tên dự án</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Vai trò</label>
                                    <input type="text" class="form-control" name="role" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày bắt đầu</label>
                                    <input type="date" class="form-control" name="start_date" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày kết thúc</label>
                                    <input type="date" class="form-control" name="end_date">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">URL dự án</label>
                                    <input type="url" class="form-control" name="url" placeholder="https://">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mô tả dự án</label>
                                    <textarea class="form-control" name="description" rows="3" required></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" onclick="submitProject()">Lưu</button>
                        </div>
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
                            <div class="project-item border-bottom pb-3 mb-3" data-id="<?= $project['id'] ?>">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($project['title']) ?></h6>
                                        <p class="mb-1"><?= htmlspecialchars($project['role']) ?></p>
                                        <p class="text-muted small mb-1">
                                            <?= date('m/Y', strtotime($project['start_date'])) ?> -
                                            <?= $project['end_date'] ? date('m/Y', strtotime($project['end_date'])) : 'Hiện tại' ?>
                                        </p>
                                        <?php if ($project['url']): ?>
                                            <p class="mb-1">
                                                <a href="<?= htmlspecialchars($project['url']) ?>" target="_blank" class="text-primary">
                                                    <i class="fas fa-external-link-alt"></i> Xem dự án
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($project['description']): ?>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($project['description'])) ?></p>
                                        <?php endif; ?>
                                    </div>
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Contact Modal -->
            <div class="modal fade" id="contactModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm thông tin liên hệ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="contactForm">
                                <input type="hidden" name="cv_id" value="<?= $cv['id'] ?>">
                                <input type="hidden" name="id" value="">
                                <div class="mb-3">
                                    <label class="form-label">Loại liên hệ</label>
                                    <select class="form-select" name="type" required>
                                        <option value="">Chọn loại liên hệ</option>
                                        <option value="website">Website</option>
                                        <option value="github">GitHub</option>
                                        <option value="linkedin">LinkedIn</option>
                                        <option value="facebook">Facebook</option>
                                        <option value="twitter">Twitter</option>
                                        <option value="email">Email</option>
                                        <option value="phone">Điện thoại</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Giá trị</label>
                                    <input type="text" class="form-control" name="value" required>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" name="is_primary" id="is_primary">
                                    <label class="form-check-label" for="is_primary">Đặt làm thông tin chính</label>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" onclick="submitContact()">Lưu</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contacts Section -->
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
                            <div class="contact-item border-bottom pb-3 mb-3" data-id="<?= $contact['id'] ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas me-2" data-contact-type="<?= htmlspecialchars($contact['type']) ?>"></i>
                                            <div>
                                                <h6 class="mb-1"><?= ucfirst(htmlspecialchars($contact['type'])) ?></h6>
                                                <p class="mb-0">
                                                    <?php if ($contact['type'] === 'email'): ?>
                                                        <a href="mailto:<?= htmlspecialchars($contact['value']) ?>"><?= htmlspecialchars($contact['value']) ?></a>
                                                    <?php elseif ($contact['type'] === 'phone'): ?>
                                                        <a href="tel:<?= htmlspecialchars($contact['value']) ?>"><?= htmlspecialchars($contact['value']) ?></a>
                                                    <?php else: ?>
                                                        <a href="<?= htmlspecialchars($contact['value']) ?>" target="_blank"><?= htmlspecialchars($contact['value']) ?></a>
                                                    <?php endif; ?>
                                                    <?php if ($contact['is_primary']): ?>
                                                        <span class="badge bg-primary ms-2">Chính</span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
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

<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <button type="button" class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#previewModal">
                <i class="fas fa-eye me-2"></i>Xem trước CV
            </button>
            <div class="modal-body p-0">
                <div class="cv-preview-wrapper">
                    <div id="cvPreview" class="cv-preview-container" data-template="<?= $cv['template_id'] ?>">
                        <!-- CV content will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="printCV()">
                    <i class="fas fa-print me-2"></i>In CV
                </button>
                
                <button type="button" class="btn btn-success" onclick="exportPDF()">Xuất PDF</button>
            </div>
        </div>
    </div>
</div>


<!-- At the bottom of the file, before footer -->
<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>
<!-- Add this before the existing scripts -->
<script src="<?= BASE_URL ?>/public/js/cv/preview.js"></script>
<script src="<?= BASE_URL ?>/public/js/cv/utils.js"></script>
<script src="<?= BASE_URL ?>/public/js/cv/experience.js"></script>
<script src="<?= BASE_URL ?>/public/js/cv/education.js"></script>
<script src="<?= BASE_URL ?>/public/js/cv/skills.js"></script>
<script src="<?= BASE_URL ?>/public/js/cv/languages.js"></script>
<script src="<?= BASE_URL ?>/public/js/cv/certificates.js"></script>
<script src="<?= BASE_URL ?>/public/js/cv/projects.js"></script>
<script src="<?= BASE_URL ?>/public/js/cv/contacts.js"></script>

<?php require_once 'app/views/layouts/footer.php'; ?>