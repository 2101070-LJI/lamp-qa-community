<?php
require_once __DIR__ . '/../config/database.php';

class Answer {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByQuestion(int $questionId): array {
        $stmt = $this->db->prepare(
            'SELECT a.*, u.username FROM answers a
             JOIN users u ON a.user_id = u.id
             WHERE a.question_id = ?
             ORDER BY a.is_accepted DESC, a.vote_count DESC, a.created_at ASC'
        );
        $stmt->execute([$questionId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            'SELECT a.*, u.username FROM answers a
             JOIN users u ON a.user_id = u.id
             WHERE a.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(int $questionId, int $userId, string $body): int {
        $stmt = $this->db->prepare(
            'INSERT INTO answers (question_id, user_id, body) VALUES (?, ?, ?)'
        );
        $stmt->execute([$questionId, $userId, $body]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $body): void {
        $stmt = $this->db->prepare('UPDATE answers SET body = ? WHERE id = ?');
        $stmt->execute([$body, $id]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare('DELETE FROM answers WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function accept(int $id, int $questionId): void {
        // 기존 채택 해제
        $stmt = $this->db->prepare('UPDATE answers SET is_accepted = 0 WHERE question_id = ?');
        $stmt->execute([$questionId]);
        // 채택
        $stmt = $this->db->prepare('UPDATE answers SET is_accepted = 1 WHERE id = ?');
        $stmt->execute([$id]);
    }
}
