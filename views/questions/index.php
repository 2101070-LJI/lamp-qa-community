<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="index-layout">
    <div class="questions-main">
        <div class="questions-header">
            <h2>질문 목록 <?= isset($filterTag) && $filterTag !== '' ? '— <span class="tag">' . h($filterTag) . '</span>' : '' ?></h2>
            <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/questions/create" class="btn btn-primary">질문하기</a>
            <?php endif; ?>
        </div>

        <?php if (empty($questions)): ?>
            <p class="empty-msg">아직 질문이 없습니다. 첫 질문을 작성해보세요!</p>
        <?php else: ?>
            <ul class="question-list">
                <?php foreach ($questions as $q): ?>
                <li class="question-item <?= $q['is_solved'] ? 'solved' : '' ?>">
                    <div class="question-stats">
                        <span class="votes"><?= (int)$q['vote_count'] ?> 추천</span>
                        <span class="views"><?= (int)$q['view_count'] ?> 조회</span>
                        <?php if ($q['is_solved']): ?>
                            <span class="badge-solved">채택됨</span>
                        <?php endif; ?>
                    </div>
                    <div class="question-content">
                        <a href="<?= BASE_URL ?>/questions/<?= (int)$q['id'] ?>">
                            <?= h($q['title']) ?>
                        </a>
                        <?php if (!empty($q['tags'])): ?>
                        <div class="tag-list">
                            <?php foreach ($q['tags'] as $tag): ?>
                                <a href="<?= BASE_URL ?>/?tag=<?= h(urlencode($tag['name'])) ?>" class="tag"><?= h($tag['name']) ?></a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <div class="question-meta">
                            <span><?= h($q['username']) ?></span>
                            <span><?= h($q['created_at']) ?></span>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>

            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?><?= isset($filterTag) && $filterTag !== '' ? '&tag=' . h(urlencode($filterTag)) : '' ?>"
                       class="<?= $i === $currentPage ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <aside class="sidebar">
        <h3>태그</h3>
        <?php if (!empty($popularTags)): ?>
        <div class="tag-cloud">
            <?php foreach ($popularTags as $tag): ?>
                <a href="<?= BASE_URL ?>/?tag=<?= h(urlencode($tag['name'])) ?>" class="tag">
                    <?= h($tag['name']) ?> <span class="tag-count"><?= (int)$tag['question_count'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="empty-msg">등록된 태그가 없습니다.</p>
        <?php endif; ?>
    </aside>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
