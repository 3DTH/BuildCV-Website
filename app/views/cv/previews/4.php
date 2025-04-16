<div class="cv-document minimal-white">
    <div class="cv-header">
        <h1><?= htmlspecialchars(strtoupper($personal['name'])) ?></h1>
        <p class="position"><?= htmlspecialchars($cv['title'] ?? '') ?></p>
        
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

    <?php if (!empty($cv['summary'])): ?>
    <div class="cv-section">
        <h2 class="section-title">Giới thiệu</h2>
        <div class="section-content">
            <?= nl2br(htmlspecialchars($cv['summary'])) ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($experience)): ?>
    <div class="cv-section">
        <h2 class="section-title">Kinh nghiệm</h2>
        <?php foreach ($experience as $exp): ?>
        <div class="timeline-item">
            <div class="item-header">
                <div>
                    <h3 class="item-title"><?= htmlspecialchars($exp['position']) ?></h3>
                    <h4 class="item-subtitle"><?= htmlspecialchars($exp['company']) ?></h4>
                </div>
                <span class="period">
                    <?= !empty($exp['start_date']) ? date('m/Y', strtotime($exp['start_date'])) : '' ?> - 
                    <?= isset($exp['is_current']) && $exp['is_current'] ? 'Hiện tại' : 
                        (!empty($exp['end_date']) ? date('m/Y', strtotime($exp['end_date'])) : '') ?>
                </span>
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
    <div class="cv-section">
        <h2 class="section-title">Học vấn</h2>
        <?php foreach ($education as $edu): ?>
        <div class="timeline-item">
            <div class="item-header">
                <div>
                    <h3 class="item-title"><?= htmlspecialchars($edu['degree']) ?></h3>
                    <h4 class="item-subtitle"><?= htmlspecialchars($edu['institution']) ?></h4>
                </div>
                <span class="period">
                    <?= !empty($edu['start_date']) ? date('m/Y', strtotime($edu['start_date'])) : '' ?> - 
                    <?= !empty($edu['end_date']) ? date('m/Y', strtotime($edu['end_date'])) : '' ?>
                </span>
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

    <?php if (!empty($skills)): ?>
    <div class="cv-section">
        <h2 class="section-title">Kỹ năng</h2>
        <div class="skills-grid">
            <?php foreach ($skills as $skill): ?>
            <div class="skill-item">
                <span class="skill-name"><?= htmlspecialchars($skill['name']) ?></span>
                <div class="skill-level">
                    <div class="skill-progress" style="width: <?= htmlspecialchars($skill['level']) ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($languages)): ?>
    <div class="cv-section">
        <h2 class="section-title">Ngoại ngữ</h2>
        <div class="language-grid">
            <?php foreach ($languages as $lang): ?>
            <div class="language-item">
                <span class="language-name"><?= htmlspecialchars($lang['name']) ?></span>
                <span class="language-level"><?= htmlspecialchars($lang['proficiency']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($certificates)): ?>
    <div class="cv-section">
        <h2 class="section-title">Chứng chỉ</h2>
        <?php foreach ($certificates as $cert): ?>
        <div class="timeline-item">
            <div class="item-header">
                <div>
                    <h3 class="item-title"><?= htmlspecialchars($cert['name']) ?></h3>
                    <h4 class="item-subtitle"><?= htmlspecialchars($cert['issuer']) ?></h4>
                </div>
                <span class="period">
                    <?= !empty($cert['issue_date']) ? date('m/Y', strtotime($cert['issue_date'])) : '' ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>