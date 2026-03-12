<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="auth-box">
    <h2>회원가입</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $e): ?>
                <p><?= h($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="<?= BASE_URL ?>/register">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <div class="form-group">
            <label for="username">사용자 이름</label>
            <input type="text" id="username" name="username"
                   value="<?= h($_POST['username'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="email">이메일</label>
            <input type="email" id="email" name="email"
                   value="<?= h($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="password">비밀번호 <small>(8자 이상)</small></label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirm">비밀번호 확인</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
        </div>
        <button type="submit" class="btn btn-primary">가입하기</button>
    </form>
    <p class="auth-link">이미 계정이 있으신가요? <a href="<?= BASE_URL ?>/login">로그인</a></p>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
