<?php
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../models/Answer.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../models/Vote.php';
require_once __DIR__ . '/../includes/functions.php';

class QuestionController {
    private Question $questionModel;
    private Answer   $answerModel;
    private Tag      $tagModel;
    private Vote     $voteModel;

    public function __construct() {
        $this->questionModel = new Question();
        $this->answerModel   = new Answer();
        $this->tagModel      = new Tag();
        $this->voteModel     = new Vote();
    }

    public function index(): void {
        $currentPage = max(1, (int)($_GET['page'] ?? 1));
        $filterTag   = trim($_GET['tag'] ?? '');

        if ($filterTag !== '') {
            $tag       = $this->tagModel->findByName($filterTag);
            $questions = $tag ? $this->questionModel->getAllByTag((int)$tag['id'], $currentPage) : [];
            $total     = $tag ? $this->questionModel->countByTag((int)$tag['id']) : 0;
        } else {
            $questions = $this->questionModel->getAll($currentPage);
            $total     = $this->questionModel->getTotalCount();
        }

        // 각 질문에 태그 붙이기
        foreach ($questions as &$q) {
            $q['tags'] = $this->tagModel->getByQuestion((int)$q['id']);
        }
        unset($q);

        $totalPages  = (int) ceil($total / $this->questionModel->getPerPage());
        $popularTags = $this->tagModel->getAll();

        require __DIR__ . '/../views/questions/index.php';
    }

    public function show(int $id): void {
        $question = $this->questionModel->findById($id);
        if (!$question) {
            http_response_code(404);
            echo '<h1>질문을 찾을 수 없습니다.</h1>';
            return;
        }
        $this->questionModel->incrementViewCount($id);
        $answers  = $this->answerModel->getByQuestion($id);
        $tags     = $this->tagModel->getByQuestion($id);

        // 로그인 사용자 투표 현황
        $userVotes = [];
        if (isLoggedIn()) {
            $answerIds = array_column($answers, 'id');
            $qVotes    = $this->voteModel->getUserVotes($_SESSION['user_id'], 'question', [$id]);
            $aVotes    = $this->voteModel->getUserVotes($_SESSION['user_id'], 'answer', $answerIds);
            $userVotes = ['question' => $qVotes, 'answer' => $aVotes];
        }

        require __DIR__ . '/../views/questions/show.php';
    }

    public function create(): void {
        requireLogin();
        require __DIR__ . '/../views/questions/create.php';
    }

    public function store(): void {
        requireLogin();
        $errors  = [];
        $title   = trim($_POST['title'] ?? '');
        $body    = trim($_POST['body'] ?? '');
        $tagsRaw = trim($_POST['tags'] ?? '');
        $token   = $_POST['csrf_token'] ?? '';

        if (!verifyCsrfToken($token)) {
            $errors[] = '잘못된 요청입니다.';
        }
        if (empty($title) || strlen($title) < 5) {
            $errors[] = '제목은 5자 이상이어야 합니다.';
        }
        if (empty($body) || strlen($body) < 10) {
            $errors[] = '내용은 10자 이상이어야 합니다.';
        }

        if (!empty($errors)) {
            require __DIR__ . '/../views/questions/create.php';
            return;
        }

        $id       = $this->questionModel->create($_SESSION['user_id'], $title, $body);
        $tagNames = array_filter(array_map('trim', explode(',', $tagsRaw)));
        if (!empty($tagNames)) {
            $this->tagModel->attachToQuestion($id, $tagNames);
        }

        redirect('/questions/' . $id);
    }

    public function edit(int $id): void {
        requireLogin();
        $question = $this->questionModel->findById($id);
        if (!$question || $question['user_id'] !== $_SESSION['user_id']) {
            redirect('/');
        }
        $tags = $this->tagModel->getByQuestion($id);
        require __DIR__ . '/../views/questions/edit.php';
    }

    public function update(int $id): void {
        requireLogin();
        $question = $this->questionModel->findById($id);
        if (!$question || $question['user_id'] !== $_SESSION['user_id']) {
            redirect('/');
        }

        $errors  = [];
        $title   = trim($_POST['title'] ?? '');
        $body    = trim($_POST['body'] ?? '');
        $tagsRaw = trim($_POST['tags'] ?? '');
        $token   = $_POST['csrf_token'] ?? '';

        if (!verifyCsrfToken($token)) {
            $errors[] = '잘못된 요청입니다.';
        }
        if (empty($title) || strlen($title) < 5) {
            $errors[] = '제목은 5자 이상이어야 합니다.';
        }
        if (empty($body) || strlen($body) < 10) {
            $errors[] = '내용은 10자 이상이어야 합니다.';
        }

        if (!empty($errors)) {
            $tags = $this->tagModel->getByQuestion($id);
            require __DIR__ . '/../views/questions/edit.php';
            return;
        }

        $this->questionModel->update($id, $title, $body);
        $tagNames = array_filter(array_map('trim', explode(',', $tagsRaw)));
        $this->tagModel->attachToQuestion($id, $tagNames);

        redirect('/questions/' . $id);
    }

    public function delete(int $id): void {
        requireLogin();
        $question = $this->questionModel->findById($id);
        if (!$question || $question['user_id'] !== $_SESSION['user_id']) {
            redirect('/');
        }
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            redirect('/');
        }
        $this->questionModel->delete($id);
        redirect('/');
    }
}
