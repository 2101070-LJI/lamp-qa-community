<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="question-detail">
    <div class="question-header">
        <h2><?= h($question['title']) ?></h2>
        <div class="question-meta">
            <span>작성자: <strong><?= h($question['username']) ?></strong></span>
            <span><?= h($question['created_at']) ?></span>
            <span>조회 <?= (int)$question['view_count'] ?></span>
            <?php if ($question['is_solved']): ?><span class="badge-solved">해결됨</span><?php endif; ?>
        </div>
        <?php if (!empty($tags)): ?>
        <div class="tag-list">
            <?php foreach ($tags as $tag): ?>
                <a href="<?= BASE_URL ?>/?tag=<?= h(urlencode($tag['name'])) ?>" class="tag"><?= h($tag['name']) ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="vote-section">
        <?php
        $myQVote = $userVotes['question'][$question['id']] ?? 0;
        ?>
        <?php if (isLoggedIn() && $question['user_id'] !== $_SESSION['user_id']): ?>
        <form method="POST" action="<?= BASE_URL ?>/vote/question/<?= (int)$question['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <button name="value" value="1" class="vote-btn up <?= $myQVote === 1 ? 'active' : '' ?>">▲</button>
        </form>
        <?php else: ?>
            <span class="vote-btn up" title="<?= isLoggedIn() ? '본인 글에는 투표할 수 없습니다' : '로그인 후 투표하세요' ?>">▲</span>
        <?php endif; ?>
        <span class="vote-count"><?= (int)$question['vote_count'] ?></span>
        <?php if (isLoggedIn() && $question['user_id'] !== $_SESSION['user_id']): ?>
        <form method="POST" action="<?= BASE_URL ?>/vote/question/<?= (int)$question['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <button name="value" value="-1" class="vote-btn down <?= $myQVote === -1 ? 'active' : '' ?>">▼</button>
        </form>
        <?php else: ?>
            <span class="vote-btn down" title="<?= isLoggedIn() ? '본인 글에는 투표할 수 없습니다' : '로그인 후 투표하세요' ?>">▼</span>
        <?php endif; ?>
    </div>

    <div class="question-body">
        <?= nl2br(h($question['body'])) ?>
    </div>

    <?php if (isLoggedIn() && $question['user_id'] === $_SESSION['user_id']): ?>
    <div class="question-actions">
        <a href="<?= BASE_URL ?>/questions/<?= (int)$question['id'] ?>/edit" class="btn">수정</a>
        <form method="POST" action="<?= BASE_URL ?>/questions/<?= (int)$question['id'] ?>/delete"
              onsubmit="return confirm('삭제하시겠습니까?')">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <button type="submit" class="btn btn-danger">삭제</button>
        </form>
    </div>
    <?php endif; ?>
</div>

<hr>
<h3>답변 <?= count($answers) ?>개</h3>

<?php foreach ($answers as $answer): ?>
<?php $myAVote = $userVotes['answer'][$answer['id']] ?? 0; ?>
<div class="answer-item <?= $answer['is_accepted'] ? 'accepted' : '' ?>">
    <?php if ($answer['is_accepted']): ?><span class="badge-solved">채택된 답변</span><?php endif; ?>

    <div class="vote-section inline">
        <?php if (isLoggedIn() && $answer['user_id'] !== $_SESSION['user_id']): ?>
        <form method="POST" action="<?= BASE_URL ?>/vote/answer/<?= (int)$answer['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <button name="value" value="1" class="vote-btn up <?= $myAVote === 1 ? 'active' : '' ?>">▲</button>
        </form>
        <?php else: ?>
            <span class="vote-btn up" title="<?= isLoggedIn() ? '본인 글에는 투표할 수 없습니다' : '로그인 후 투표하세요' ?>">▲</span>
        <?php endif; ?>
        <span class="vote-count"><?= (int)$answer['vote_count'] ?></span>
        <?php if (isLoggedIn() && $answer['user_id'] !== $_SESSION['user_id']): ?>
        <form method="POST" action="<?= BASE_URL ?>/vote/answer/<?= (int)$answer['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <button name="value" value="-1" class="vote-btn down <?= $myAVote === -1 ? 'active' : '' ?>">▼</button>
        </form>
        <?php else: ?>
            <span class="vote-btn down" title="<?= isLoggedIn() ? '본인 글에는 투표할 수 없습니다' : '로그인 후 투표하세요' ?>">▼</span>
        <?php endif; ?>
    </div>

    <div class="answer-body"><?= nl2br(h($answer['body'])) ?></div>
    <div class="answer-meta">
        <span><?= h($answer['username']) ?></span>
        <span><?= h($answer['created_at']) ?></span>
    </div>

    <?php if (isLoggedIn()): ?>
    <div class="answer-actions">
        <?php if ($answer['user_id'] === $_SESSION['user_id']): ?>
            <a href="<?= BASE_URL ?>/answers/<?= (int)$answer['id'] ?>/edit" class="btn">수정</a>
            <form method="POST" action="<?= BASE_URL ?>/answers/<?= (int)$answer['id'] ?>/delete"
                  onsubmit="return confirm('삭제하시겠습니까?')">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <button type="submit" class="btn btn-danger">삭제</button>
            </form>
        <?php endif; ?>
        <?php if ($question['user_id'] === $_SESSION['user_id'] && !$question['is_solved']): ?>
            <form method="POST" action="<?= BASE_URL ?>/answers/<?= (int)$answer['id'] ?>/accept">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <button type="submit" class="btn btn-primary">베스트 답변 채택</button>
            </form>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<?php if (isLoggedIn()): ?>
<div class="answer-form">
    <h3>답변 작성</h3>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="<?= BASE_URL ?>/questions/<?= (int)$question['id'] ?>/answers">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <div class="form-group">
            <textarea name="body" rows="8" placeholder="답변을 입력하세요 (10자 이상)"><?= h($_POST['body'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">답변 등록</button>
    </form>
</div>
<?php else: ?>
    <p class="auth-notice"><a href="<?= BASE_URL ?>/login">로그인</a> 후 답변을 작성할 수 있습니다.</p>
<?php endif; ?>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
