<div class="cv-document professional-blue">
    <div class="cv-container">
        <div class="sidebar">
            <div class="profile-section">
            <div class="profile-image">
                <img src="<?= BASE_URL ?>/uploads/profile_pictures/<?= !empty($user['profile_picture']) ? $user['profile_picture'] : 'default.jpg' ?>" 
                     alt="<?= htmlspecialchars($personal['name']) ?>">
            </div>
                <h2><?= htmlspecialchars(strtoupper($personal['name'])) ?></h2>
                <p><?= htmlspecialchars($cv['title'] ?? '') ?></p>
            </div>

            <div class="contact-section">
                <h3 class="section-title">Liên hệ</h3>
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

            <?php if (!empty($skills)): ?>
            <div class="skills-section">
                <h3 class="section-title">Kỹ năng</h3>
                <?php foreach ($skills as $skill): ?>
                <div class="skill-item">
                    <div class="skill-name"><?= htmlspecialchars($skill['name']) ?></div>
                    <div class="skill-level">
                        <div class="skill-progress" style="width: <?= htmlspecialchars($skill['level']) ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($languages)): ?>
            <div class="languages-section">
                <h3 class="section-title">Ngoại ngữ</h3>
                <?php foreach ($languages as $lang): ?>
                <div class="language-item">
                    <span class="language-name"><?= htmlspecialchars($lang['name']) ?></span>
                    <span class="language-level"><?= htmlspecialchars($lang['proficiency']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="main-content">
            <?php if (!empty($cv['summary'])): ?>
            <div class="summary-section">
                <h3 class="section-title">Giới thiệu</h3>
                <p><?= nl2br(htmlspecialchars($cv['summary'])) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($experience)): ?>
            <div class="experience-section">
                <h3 class="section-title">Kinh nghiệm làm việc</h3>
                <?php foreach ($experience as $exp): ?>
                <div class="timeline-item">
                    <div class="item-header">
                        <h4 class="item-title"><?= htmlspecialchars($exp['position']) ?></h4>
                        <div class="item-subtitle"><?= htmlspecialchars($exp['company']) ?></div>
                        <div class="period">
                            <?= !empty($exp['start_date']) ? date('m/Y', strtotime($exp['start_date'])) : '' ?> - 
                            <?= isset($exp['is_current']) && $exp['is_current'] ? 'Hiện tại' : 
                                (!empty($exp['end_date']) ? date('m/Y', strtotime($exp['end_date'])) : '') ?>
                        </div>
                    </div>
                    <?php if (!empty($exp['description'])): ?>
                    <div class="description">
                        <?= nl2br(htmlspecialchars($exp['description'])) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($education)): ?>
            <div class="education-section">
                <h3 class="section-title">Học vấn</h3>
                <?php foreach ($education as $edu): ?>
                <div class="timeline-item">
                    <div class="item-header">
                        <h4 class="item-title"><?= htmlspecialchars($edu['degree']) ?></h4>
                        <div class="item-subtitle"><?= htmlspecialchars($edu['institution']) ?></div>
                        <div class="period">
                            <?= !empty($edu['start_date']) ? date('m/Y', strtotime($edu['start_date'])) : '' ?> - 
                            <?= !empty($edu['end_date']) ? date('m/Y', strtotime($edu['end_date'])) : '' ?>
                        </div>
                    </div>
                    <?php if (!empty($edu['description'])): ?>
                    <div class="description">
                        <?= nl2br(htmlspecialchars($edu['description'])) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($certificates)): ?>
            <div class="certificates-section">
                <h3 class="section-title">Chứng chỉ</h3>
                <?php foreach ($certificates as $cert): ?>
                <div class="timeline-item">
                    <div class="item-header">
                        <h4 class="item-title"><?= htmlspecialchars($cert['name']) ?></h4>
                        <div class="item-subtitle"><?= htmlspecialchars($cert['issuer']) ?></div>
                        <div class="period">
                            <?= !empty($cert['issue_date']) ? date('m/Y', strtotime($cert['issue_date'])) : '' ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>