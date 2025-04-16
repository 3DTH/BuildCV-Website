<div class="cv-document two-column">
    <div class="cv-container">
        <div class="left-column">
            <div class="header-section">
                <h1 class="name"><?= htmlspecialchars(strtoupper($personal['name'])) ?></h1>
                <p class="position"><?= htmlspecialchars($cv['title'] ?? '') ?></p>
            </div>

            <div class="section">
                <h2 class="section-title">Liên hệ</h2>
                <div class="contact-list">
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

            <?php if (!empty($skills)): ?>
            <div class="section">
                <h2 class="section-title">Kỹ năng</h2>
                <?php foreach ($skills as $skill): ?>
                <div class="skill-item">
                    <div class="skill-name">
                        <span><?= htmlspecialchars($skill['name']) ?></span>
                        <span><?= htmlspecialchars($skill['level']) ?>%</span>
                    </div>
                    <div class="skill-level">
                        <div class="skill-progress" style="width: <?= htmlspecialchars($skill['level']) ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($languages)): ?>
            <div class="section">
                <h2 class="section-title">Ngoại ngữ</h2>
                <?php foreach ($languages as $lang): ?>
                <div class="language-item">
                    <span class="language-name"><?= htmlspecialchars($lang['name']) ?></span>
                    <span class="language-level"><?= htmlspecialchars($lang['proficiency']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="right-column">
            <?php if (!empty($cv['summary'])): ?>
            <div class="section">
                <h2 class="section-title">Giới thiệu</h2>
                <p><?= nl2br(htmlspecialchars($cv['summary'])) ?></p>
            </div>
            <?php endif; ?>

            <?php if (!empty($experience)): ?>
            <div class="section">
                <h2 class="section-title">Kinh nghiệm làm việc</h2>
                <?php foreach ($experience as $exp): ?>
                <div class="timeline-item">
                    <div class="item-header">
                        <h3 class="item-title"><?= htmlspecialchars($exp['position']) ?></h3>
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
            <div class="section">
                <h2 class="section-title">Học vấn</h2>
                <?php foreach ($education as $edu): ?>
                <div class="timeline-item">
                    <div class="item-header">
                        <h3 class="item-title"><?= htmlspecialchars($edu['degree']) ?></h3>
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
            <div class="section">
                <h2 class="section-title">Chứng chỉ</h2>
                <?php foreach ($certificates as $cert): ?>
                <div class="timeline-item">
                    <div class="item-header">
                        <h3 class="item-title"><?= htmlspecialchars($cert['name']) ?></h3>
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