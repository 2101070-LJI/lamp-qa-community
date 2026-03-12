<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="form-box">
    <h2>답변 수정</h2>
    <p>질문: <a href="<?= BASE_URL ?>/questions/<?= (int)$question['id'] ?>"><?= h($question['title']) ?></a></p>
    <form method="POST" action="<?= BASE_URL ?>/answers/<?= (int)$answer['id'] ?>/edit">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <div class="form-group">
            <label for="body">내용</label>
            <textarea id="body" name="body" rows="10"><?= h($answer['body']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">저장</button>
        <a href="<?= BASE_URL ?>/questions/<?= (int)$question['id'] ?>" class="btn">취소</a>
    </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
