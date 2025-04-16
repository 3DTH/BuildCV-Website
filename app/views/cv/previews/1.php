<div class="cv-document">
    <div class="cv-header">
        <h1><?= htmlspecialchars($personal['name']) ?></h1>
        <p class="position"><?= htmlspecialchars($cv['title'] ?? '') ?></p>

        <div class="contact-info">
            <?php if (!empty($personal['email'])): ?>
                <p><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($personal['email']) ?></p>
            <?php endif; ?>
            <?php if (!empty($personal['phone'])): ?>
                <p><i class="fas fa-phone me-2"></i><?= htmlspecialchars($personal['phone']) ?></p>
            <?php endif; ?>
            <?php if (!empty($personal['address'])): ?>
                <p><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($personal['address']) ?></p>
            <?php endif; ?>
            <?php foreach ($contacts as $contact): ?>
                <p><i class="fas fa-<?= htmlspecialchars($contact['type']) ?> me-2"></i><?= htmlspecialchars($contact['value']) ?></p>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (!empty($cv['summary'])): ?>
    <div class="cv-section">
        <h2><i class="fas fa-user me-2"></i>Tóm tắt</h2>
        <div class="summary-content">
            <p><?= nl2br(htmlspecialchars($cv['summary'])) ?></p>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($experience)): ?>
    <div class="cv-section">
        <h2><i class="fas fa-briefcase me-2"></i>Kinh nghiệm làm việc</h2>
        <?php foreach ($experience as $exp): ?>
        <div class="cv-item">
            <h3><?= htmlspecialchars($exp['position']) ?></h3>
            <h4><?= htmlspecialchars($exp['company']) ?></h4>
            <p class="period">
                <?= !empty($exp['start_date']) ? date('m/Y', strtotime($exp['start_date'])) : '' ?> - 
                <?= isset($exp['is_current']) && $exp['is_current'] ? 'Hiện tại' : 
                    (!empty($exp['end_date']) ? date('m/Y', strtotime($exp['end_date'])) : '') ?>
            </p>
            <p><?= nl2br(htmlspecialchars($exp['description'])) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($education)): ?>
    <div class="cv-section">
        <h2><i class="fas fa-graduation-cap me-2"></i>Học vấn</h2>
        <?php foreach ($education as $edu): ?>
        <div class="cv-item">
            <h3><?= htmlspecialchars($edu['degree']) ?></h3>
            <h4><?= htmlspecialchars($edu['institution']) ?></h4>
            <p class="period">
                <?= !empty($edu['start_date']) ? date('m/Y', strtotime($edu['start_date'])) : '' ?> - 
                <?= !empty($edu['end_date']) ? date('m/Y', strtotime($edu['end_date'])) : '' ?>
            </p>
            <?php if (!empty($edu['description'])): ?>
                <p><?= nl2br(htmlspecialchars($edu['description'])) ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($skills)): ?>
    <div class="cv-section">
        <h2><i class="fas fa-tools me-2"></i>Kỹ năng</h2>
        <div class="skills-grid">
            <?php foreach ($skills as $skill): ?>
            <div class="skill-item">
                <span class="skill-name"><?= htmlspecialchars($skill['name']) ?></span>
                <div class="skill-level">
                    <div class="skill-bar" style="width: <?= htmlspecialchars($skill['level']) ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($languages)): ?>
    <div class="cv-section">
        <h2><i class="fas fa-language me-2"></i>Ngoại ngữ</h2>
        <div class="languages-grid">
            <?php foreach ($languages as $lang): ?>
            <div class="language-item">
                <span class="language-name"><?= htmlspecialchars($lang['name']) ?></span>
                <span class="language-level"><?= htmlspecialchars($lang['proficiency']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($projects)): ?>
    <div class="cv-section">
        <h2><i class="fas fa-project-diagram me-2"></i>Dự án tiêu biểu</h2>
        <?php foreach ($projects as $project): ?>
        <div class="cv-item">
            <h3><?= htmlspecialchars($project['title']) ?></h3>
            <h4><?= htmlspecialchars($project['role']) ?></h4>
            <p class="period">
                <?= !empty($project['start_date']) ? date('m/Y', strtotime($project['start_date'])) : '' ?> - 
                <?= !empty($project['end_date']) ? date('m/Y', strtotime($project['end_date'])) : '' ?>
            </p>
            <?php if (!empty($project['description'])): ?>
                <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($certificates)): ?>
    <div class="cv-section">
        <h2><i class="fas fa-certificate me-2"></i>Chứng chỉ</h2>
        <?php foreach ($certificates as $cert): ?>
        <div class="cv-item">
            <h3><?= htmlspecialchars($cert['name']) ?></h3>
            <h4><?= htmlspecialchars($cert['issuer']) ?></h4>
            <p class="period">
                <?= !empty($cert['issue_date']) ? date('m/Y', strtotime($cert['issue_date'])) : '' ?>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>