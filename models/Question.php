<?php
require_once __DIR__ . '/../config/database.php';

class Question {
    private PDO $db;
    private int $perPage = 10;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(int $page = 1): array {
        $offset = ($page - 1) * $this->perPage;
        $stmt = $this->db->prepare(
            'SELECT q.*, u.username FROM questions q
             JOIN users u ON q.user_id = u.id
             ORDER BY q.created_at DESC
             LIMIT ? OFFSET ?'
        );
        $stmt->execute([$this->perPage, $offset]);
        return $stmt->fetchAll();
    }

    public function getTotalCount(): int {
        return (int) $this->db->query('SELECT COUNT(*) FROM questions')->fetchColumn();
    }

    public function getPerPage(): int {
        return $this->perPage;
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            'SELECT q.*, u.username FROM questions q
             JOIN users u ON q.user_id = u.id
             WHERE q.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(int $userId, string $title, string $body): int {
        $stmt = $this->db->prepare(
            'INSERT INTO questions (user_id, title, body) VALUES (?, ?, ?)'
        );
        $stmt->execute([$userId, $title, $body]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $title, string $body): void {
        $stmt = $this->db->prepare(
            'UPDATE questions SET title = ?, body = ? WHERE id = ?'
        );
        $stmt->execute([$title, $body, $id]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare('DELETE FROM questions WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function incrementViewCount(int $id): void {
        $stmt = $this->db->prepare('UPDATE questions SET view_count = view_count + 1 WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function markSolved(int $id): void {
        $stmt = $this->db->prepare('UPDATE questions SET is_solved = 1 WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function getAllByTag(int $tagId, int $page = 1): array {
        $offset = ($page - 1) * $this->perPage;
        $stmt = $this->db->prepare(
            'SELECT q.*, u.username FROM questions q
             JOIN users u ON q.user_id = u.id
             JOIN question_tags qt ON q.id = qt.question_id
             WHERE qt.tag_id = ?
             ORDER BY q.created_at DESC
             LIMIT ? OFFSET ?'
        );
        $stmt->execute([$tagId, $this->perPage, $offset]);
        return $stmt->fetchAll();
    }

    public function countByTag(int $tagId): int {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM questions q
             JOIN question_tags qt ON q.id = qt.question_id
             WHERE qt.tag_id = ?'
        );
        $stmt->execute([$tagId]);
        return (int) $stmt->fetchColumn();
    }
}
