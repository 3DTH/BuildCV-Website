<div class="cv-document modern-dark">
    <!-- Header Section with Dark Background -->
    <div class="cv-header">
        <div class="profile-section">
            <div class="profile-image">
                <img src="<?= BASE_URL ?>/uploads/profile_pictures/<?= !empty($user['profile_picture']) ? $user['profile_picture'] : 'default.jpg' ?>" 
                     alt="<?= htmlspecialchars($personal['name']) ?>">
            </div>
            <div class="profile-info">
                <h1><?= htmlspecialchars(strtoupper($personal['name'])) ?></h1>
                <p class="position"><?= htmlspecialchars(strtoupper($cv['title'] ?? '')) ?></p>
                <div class="contact-info">
                    <?php if (!empty($personal['email'])): ?>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span><?= htmlspecialchars($personal['email']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($personal['phone'])): ?>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span><?= htmlspecialchars($personal['phone']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($personal['address'])): ?>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?= htmlspecialchars($personal['address']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php foreach ($contacts as $contact): ?>
                    <div class="contact-item">
                        <i class="fas fa-<?= htmlspecialchars($contact['type']) ?>"></i>
                        <span><?= htmlspecialchars($contact['value']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="cv-body">
        <div class="main-content">
            <?php if (!empty($cv['summary'])): ?>
            <div class="cv-section summary-section">
                <h2><i class="fas fa-user-tie"></i>GIỚI THIỆU CHUYÊN MÔN</h2>
                <div class="section-content">
                    <p><?= nl2br(htmlspecialchars($cv['summary'])) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($experience)): ?>
            <div class="cv-section">
                <h2><i class="fas fa-briefcase"></i>KINH NGHIỆM LÀM VIỆC</h2>
                <div class="section-content">
                    <?php foreach ($experience as $exp): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h3><?= htmlspecialchars($exp['position']) ?></h3>
                            <h4><?= htmlspecialchars($exp['company']) ?></h4>
                            <p class="period">
                                <?= !empty($exp['start_date']) ? date('m/Y', strtotime($exp['start_date'])) : '' ?> - 
                                <?= isset($exp['is_current']) && $exp['is_current'] ? 'Hiện tại' : 
                                    (!empty($exp['end_date']) ? date('m/Y', strtotime($exp['end_date'])) : '') ?>
                            </p>
                            <?php if (!empty($exp['description'])): ?>
                            <div class="description">
                                <?= nl2br(htmlspecialchars($exp['description'])) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($education)): ?>
            <div class="cv-section">
                <h2><i class="fas fa-graduation-cap"></i>HỌC VẤN</h2>
                <div class="section-content">
                    <?php foreach ($education as $edu): ?>
                    <div class="education-item">
                        <h3><?= htmlspecialchars($edu['degree']) ?></h3>
                        <h4><?= htmlspecialchars($edu['institution']) ?></h4>
                        <p class="period">
                            <?= !empty($edu['start_date']) ? date('m/Y', strtotime($edu['start_date'])) : '' ?> - 
                            <?= !empty($edu['end_date']) ? date('m/Y', strtotime($edu['end_date'])) : '' ?>
                        </p>
                        <?php if (!empty($edu['description'])): ?>
                            <div class="description">
                                <?= nl2br(htmlspecialchars($edu['description'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="side-content">
            <?php if (!empty($skills)): ?>
            <div class="cv-section skills-section">
                <h2><i class="fas fa-code"></i>KỸ NĂNG CHUYÊN MÔN</h2>
                <div class="section-content">
                    <div class="skill-category">
                        <?php foreach ($skills as $skill): ?>
                        <div class="skill-item">
                            <span><?= htmlspecialchars($skill['name']) ?></span>
                            <div class="skill-level <?= $skill['level'] >= 80 ? 'expert' : 'advanced' ?>"></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($certificates)): ?>
            <div class="cv-section">
                <h2><i class="fas fa-certificate"></i>CHỨNG CHỈ</h2>
                <div class="section-content">
                    <?php foreach ($certificates as $cert): ?>
                    <div class="certificate-item">
                        <h3><?= htmlspecialchars($cert['name']) ?></h3>
                        <h4><?= htmlspecialchars($cert['issuer']) ?></h4>
                        <p class="period">
                            <?= !empty($cert['issue_date']) ? date('m/Y', strtotime($cert['issue_date'])) : '' ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($languages)): ?>
            <div class="cv-section">
                <h2><i class="fas fa-language"></i>NGOẠI NGỮ</h2>
                <div class="section-content">
                    <?php foreach ($languages as $lang): ?>
                    <div class="language-item">
                        <span><?= htmlspecialchars($lang['name']) ?></span>
                        <div class="language-level">
                            <span><?= htmlspecialchars($lang['proficiency']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>