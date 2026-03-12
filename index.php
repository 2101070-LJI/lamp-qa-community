<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

session_set_cookie_params(['lifetime' => SESSION_LIFETIME, 'httponly' => true, 'samesite' => 'Lax']);
session_start();

$requestUri  = $_SERVER['REQUEST_URI'];
$basePath    = parse_url(BASE_URL, PHP_URL_PATH); // /project1
$path        = '/' . ltrim(substr($requestUri, strlen($basePath)), '/');
$path        = strtok($path, '?');
$method      = $_SERVER['REQUEST_METHOD'];

// DEBUG
file_put_contents('/tmp/route_debug.log',
    date('H:i:s') . " METHOD=$method PATH=$path URI=$requestUri\n", FILE_APPEND);

// Router
switch (true) {
    // Home – question list
    case $path === '/' && $method === 'GET':
        require_once __DIR__ . '/controllers/QuestionController.php';
        (new QuestionController())->index();
        break;

    // Auth
    case $path === '/login' && $method === 'GET':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->showLogin();
        break;

    case $path === '/login' && $method === 'POST':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->login();
        break;

    case $path === '/register' && $method === 'GET':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->showRegister();
        break;

    case $path === '/register' && $method === 'POST':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->register();
        break;

    case $path === '/logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    // Questions
    case $path === '/questions/create' && $method === 'GET':
        require_once __DIR__ . '/controllers/QuestionController.php';
        (new QuestionController())->create();
        break;

    case $path === '/questions/create' && $method === 'POST':
        require_once __DIR__ . '/controllers/QuestionController.php';
        (new QuestionController())->store();
        break;

    case preg_match('#^/questions/(\d+)/edit$#', $path, $m) && $method === 'GET':
        require_once __DIR__ . '/controllers/QuestionController.php';
        (new QuestionController())->edit((int)$m[1]);
        break;

    case preg_match('#^/questions/(\d+)/edit$#', $path, $m) && $method === 'POST':
        require_once __DIR__ . '/controllers/QuestionController.php';
        (new QuestionController())->update((int)$m[1]);
        break;

    case preg_match('#^/questions/(\d+)/delete$#', $path, $m) && $method === 'POST':
        require_once __DIR__ . '/controllers/QuestionController.php';
        (new QuestionController())->delete((int)$m[1]);
        break;

    case preg_match('#^/questions/(\d+)$#', $path, $m) && $method === 'GET':
        require_once __DIR__ . '/controllers/QuestionController.php';
        (new QuestionController())->show((int)$m[1]);
        break;

    // Answers
    case preg_match('#^/questions/(\d+)/answers$#', $path, $m) && $method === 'POST':
        require_once __DIR__ . '/controllers/AnswerController.php';
        (new AnswerController())->store((int)$m[1]);
        break;

    case preg_match('#^/answers/(\d+)/edit$#', $path, $m) && $method === 'GET':
        require_once __DIR__ . '/controllers/AnswerController.php';
        (new AnswerController())->edit((int)$m[1]);
        break;

    case preg_match('#^/answers/(\d+)/edit$#', $path, $m) && $method === 'POST':
        require_once __DIR__ . '/controllers/AnswerController.php';
        (new AnswerController())->update((int)$m[1]);
        break;

    case preg_match('#^/answers/(\d+)/delete$#', $path, $m) && $method === 'POST':
        require_once __DIR__ . '/controllers/AnswerController.php';
        (new AnswerController())->delete((int)$m[1]);
        break;

    case preg_match('#^/answers/(\d+)/accept$#', $path, $m) && $method === 'POST':
        require_once __DIR__ . '/controllers/AnswerController.php';
        (new AnswerController())->accept((int)$m[1]);
        break;

    // Votes
    case preg_match('#^/vote/(question|answer)/(\d+)$#', $path, $m) && $method === 'POST':
        require_once __DIR__ . '/controllers/VoteController.php';
        (new VoteController())->vote($m[1], (int)$m[2]);
        break;

    // Profile
    case $path === '/profile' && $method === 'GET':
        require_once __DIR__ . '/controllers/ProfileController.php';
        (new ProfileController())->show();
        break;

    // 404
    default:
        http_response_code(404);
        echo '<h1>404 - 페이지를 찾을 수 없습니다.</h1>';
        break;
}
