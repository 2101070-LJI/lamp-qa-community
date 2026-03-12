<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin(): void {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function showRegister(): void {
        require __DIR__ . '/../views/auth/register.php';
    }

    public function login(): void {
        $errors = [];
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $errors[] = '이메일과 비밀번호를 입력하세요.';
        }

        if (empty($errors)) {
            $user = $this->userModel->findByEmail($email);
            if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
                $errors[] = '이메일 또는 비밀번호가 올바르지 않습니다.';
            }
        }

        if (!empty($errors)) {
            require __DIR__ . '/../views/auth/login.php';
            return;
        }

        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        header('Location: ' . BASE_URL . '/');
        exit;
    }

    public function register(): void {
        $errors   = [];
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        if (empty($username) || strlen($username) < 2 || strlen($username) > 50) {
            $errors[] = '사용자 이름은 2~50자여야 합니다.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = '유효한 이메일 주소를 입력하세요.';
        }
        if (strlen($password) < 8) {
            $errors[] = '비밀번호는 8자 이상이어야 합니다.';
        }
        if ($password !== $confirm) {
            $errors[] = '비밀번호가 일치하지 않습니다.';
        }

        if (empty($errors)) {
            if ($this->userModel->findByEmail($email)) {
                $errors[] = '이미 사용 중인 이메일입니다.';
            }
            if ($this->userModel->findByUsername($username)) {
                $errors[] = '이미 사용 중인 사용자 이름입니다.';
            }
        }

        if (!empty($errors)) {
            require __DIR__ . '/../views/auth/register.php';
            return;
        }

        $this->userModel->create($username, $email, $password);
        $_SESSION['flash'] = '회원가입이 완료되었습니다. 로그인하세요.';
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    public function logout(): void {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
