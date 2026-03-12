<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="form-box">
    <h2>질문 작성</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="<?= BASE_URL ?>/questions/create">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <div class="form-group">
            <label for="title">제목 <small>(5자 이상)</small></label>
            <input type="text" id="title" name="title" value="<?= h($_POST['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="body">내용 <small>(10자 이상)</small></label>
            <textarea id="body" name="body" rows="12"><?= h($_POST['body'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="tags">태그 <small>(쉼표로 구분, 예: php, mysql, apache)</small></label>
            <input type="text" id="tags" name="tags" value="<?= h($_POST['tags'] ?? '') ?>"
                   placeholder="php, mysql, apache">
        </div>
        <button type="submit" class="btn btn-primary">질문 등록</button>
        <a href="<?= BASE_URL ?>/" class="btn">취소</a>
    </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
