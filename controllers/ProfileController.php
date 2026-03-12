<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

class ProfileController {
    public function show(): void {
        requireLogin();
        $userModel = new User();
        $user      = $userModel->findById($_SESSION['user_id']);

        $db = Database::getConnection();
        $stmt = $db->prepare(
            'SELECT * FROM questions WHERE user_id = ? ORDER BY created_at DESC LIMIT 10'
        );
        $stmt->execute([$_SESSION['user_id']]);
        $myQuestions = $stmt->fetchAll();

        $stmt = $db->prepare(
            'SELECT a.*, q.title AS question_title FROM answers a
             JOIN questions q ON a.question_id = q.id
             WHERE a.user_id = ? ORDER BY a.created_at DESC LIMIT 10'
        );
        $stmt->execute([$_SESSION['user_id']]);
        $myAnswers = $stmt->fetchAll();

        require __DIR__ . '/../views/profile/show.php';
    }
}
