<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="profile-box">
    <h2>프로필</h2>
    <div class="profile-info">
        <p><strong>사용자명:</strong> <?= h($user['username']) ?></p>
        <p><strong>이메일:</strong> <?= h($user['email']) ?></p>
        <p><strong>포인트:</strong> <?= (int)$user['points'] ?></p>
        <p><strong>가입일:</strong> <?= h($user['created_at']) ?></p>
    </div>

    <h3>내 질문 (최근 10개)</h3>
    <?php if (empty($myQuestions)): ?>
        <p class="empty-msg">아직 작성한 질문이 없습니다.</p>
    <?php else: ?>
        <ul class="question-list">
            <?php foreach ($myQuestions as $q): ?>
            <li class="question-item <?= $q['is_solved'] ? 'solved' : '' ?>">
                <div class="question-content">
                    <a href="<?= BASE_URL ?>/questions/<?= (int)$q['id'] ?>"><?= h($q['title']) ?></a>
                    <div class="question-meta">
                        <span><?= h($q['created_at']) ?></span>
                        <?php if ($q['is_solved']): ?><span class="badge-solved">채택됨</span><?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h3>내 답변 (최근 10개)</h3>
    <?php if (empty($myAnswers)): ?>
        <p class="empty-msg">아직 작성한 답변이 없습니다.</p>
    <?php else: ?>
        <ul class="question-list">
            <?php foreach ($myAnswers as $a): ?>
            <li class="question-item <?= $a['is_accepted'] ? 'solved' : '' ?>">
                <div class="question-content">
                    <a href="<?= BASE_URL ?>/questions/<?= (int)$a['question_id'] ?>">
                        <?= h($a['question_title']) ?>
                    </a>
                    <div class="question-meta">
                        <span><?= h($a['created_at']) ?></span>
                        <?php if ($a['is_accepted']): ?><span class="badge-solved">채택됨</span><?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
