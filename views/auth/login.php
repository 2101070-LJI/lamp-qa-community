<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="auth-box">
    <h2>로그인</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $e): ?>
                <p><?= h($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="<?= BASE_URL ?>/login">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <div class="form-group">
            <label for="email">이메일</label>
            <input type="email" id="email" name="email"
                   value="<?= h($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="password">비밀번호</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">로그인</button>
    </form>
    <p class="auth-link">계정이 없으신가요? <a href="<?= BASE_URL ?>/register">회원가입</a></p>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
