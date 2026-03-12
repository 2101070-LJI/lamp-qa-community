<?php
require_once __DIR__ . '/../models/Answer.php';
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/functions.php';

class AnswerController {
    private Answer $answerModel;
    private Question $questionModel;

    public function __construct() {
        $this->answerModel  = new Answer();
        $this->questionModel = new Question();
    }

    public function store(int $questionId): void {
        requireLogin();
        $errors = [];
        $body   = trim($_POST['body'] ?? '');
        $token  = $_POST['csrf_token'] ?? '';

        if (!verifyCsrfToken($token)) {
            $errors[] = '잘못된 요청입니다.';
        }
        if (empty($body) || strlen($body) < 10) {
            $errors[] = '답변은 10자 이상이어야 합니다.';
        }

        $question = $this->questionModel->findById($questionId);
        if (!$question) {
            redirect('/');
        }

        if (!empty($errors)) {
            $answers = $this->answerModel->getByQuestion($questionId);
            require __DIR__ . '/../views/questions/show.php';
            return;
        }

        $this->answerModel->create($questionId, $_SESSION['user_id'], $body);

        // 포인트: 답변 작성 +5
        $userModel = new User();
        $userModel->addPoints($_SESSION['user_id'], 5);

        redirect('/questions/' . $questionId);
    }

    public function edit(int $id): void {
        requireLogin();
        $answer = $this->answerModel->findById($id);
        if (!$answer || $answer['user_id'] !== $_SESSION['user_id']) {
            redirect('/');
        }
        $question = $this->questionModel->findById($answer['question_id']);
        require __DIR__ . '/../views/questions/edit_answer.php';
    }

    public function update(int $id): void {
        requireLogin();
        $answer = $this->answerModel->findById($id);
        if (!$answer || $answer['user_id'] !== $_SESSION['user_id']) {
            redirect('/');
        }
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            redirect('/');
        }
        $body = trim($_POST['body'] ?? '');
        if (strlen($body) >= 10) {
            $this->answerModel->update($id, $body);
        }
        redirect('/questions/' . $answer['question_id']);
    }

    public function delete(int $id): void {
        requireLogin();
        $answer = $this->answerModel->findById($id);
        if (!$answer || $answer['user_id'] !== $_SESSION['user_id']) {
            redirect('/');
        }
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            redirect('/');
        }
        $questionId = $answer['question_id'];
        $this->answerModel->delete($id);
        redirect('/questions/' . $questionId);
    }

    public function accept(int $answerId): void {
        requireLogin();
        $answer   = $this->answerModel->findById($answerId);
        if (!$answer) redirect('/');

        $question = $this->questionModel->findById($answer['question_id']);
        if (!$question || $question['user_id'] !== $_SESSION['user_id']) {
            redirect('/questions/' . $answer['question_id']);
        }
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            redirect('/');
        }

        $this->answerModel->accept($answerId, $answer['question_id']);
        $this->questionModel->markSolved($answer['question_id']);

        // 포인트: 채택된 답변 작성자 +15
        $userModel = new User();
        $userModel->addPoints($answer['user_id'], 15);

        redirect('/questions/' . $answer['question_id']);
    }
}
