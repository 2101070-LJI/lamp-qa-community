<?php
require_once __DIR__ . '/../models/Vote.php';
require_once __DIR__ . '/../includes/functions.php';

class VoteController {
    private Vote $voteModel;

    public function __construct() {
        $this->voteModel = new Vote();
    }

    public function vote(string $targetType, int $targetId): void {
        requireLogin();

        if (!in_array($targetType, ['question', 'answer'], true)) {
            $_SESSION['flash'] = "[DEBUG] 잘못된 target_type: $targetType";
            redirect('/');
        }

        $value = (int)($_POST['value'] ?? 0);
        if (!in_array($value, [1, -1], true)) {
            $_SESSION['flash'] = "[DEBUG] 잘못된 value: $value";
            redirect('/');
        }

        $submittedToken = $_POST['csrf_token'] ?? '';
        $sessionToken   = $_SESSION['csrf_token'] ?? '';

        if (!verifyCsrfToken($submittedToken)) {
            $_SESSION['flash'] = "[DEBUG] CSRF 실패 / submitted=" .
                substr($submittedToken, 0, 8) . " / session=" .
                substr($sessionToken, 0, 8);
            redirect('/');
        }

        // 자기 글에 투표 방지
        $db    = Database::getConnection();
        $table = $targetType === 'question' ? 'questions' : 'answers';
        $stmt  = $db->prepare("SELECT user_id FROM {$table} WHERE id = ?");
        $stmt->execute([$targetId]);
        $row = $stmt->fetch();

        if (!$row) {
            $_SESSION['flash'] = "[DEBUG] 대상 글 없음";
            redirect('/');
        }
        if ((int)$row['user_id'] === $_SESSION['user_id']) {
            $_SESSION['flash'] = "[DEBUG] 본인 글 투표 시도";
            redirect('/');
        }

        $this->voteModel->cast($_SESSION['user_id'], $targetType, $targetId, $value);

        $questionId = $targetId;
        if ($targetType === 'answer') {
            $stmt = $db->prepare('SELECT question_id FROM answers WHERE id = ?');
            $stmt->execute([$targetId]);
            $ans = $stmt->fetch();
            $questionId = $ans ? (int)$ans['question_id'] : 0;
        }

        redirect($questionId > 0 ? '/questions/' . $questionId : '/');
    }
}
