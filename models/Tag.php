<?php
require_once __DIR__ . '/../config/database.php';

class Tag {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findOrCreate(string $name): int {
        $name = strtolower(trim($name));
        $stmt = $this->db->prepare('SELECT id FROM tags WHERE name = ?');
        $stmt->execute([$name]);
        $tag = $stmt->fetch();
        if ($tag) return (int)$tag['id'];

        $this->db->prepare('INSERT INTO tags (name) VALUES (?)')->execute([$name]);
        return (int)$this->db->lastInsertId();
    }

    public function attachToQuestion(int $questionId, array $tagNames): void {
        // 기존 태그 제거
        $this->db->prepare('DELETE FROM question_tags WHERE question_id = ?')->execute([$questionId]);

        foreach (array_unique($tagNames) as $name) {
            $name = trim($name);
            if ($name === '') continue;
            $tagId = $this->findOrCreate($name);
            $stmt  = $this->db->prepare(
                'INSERT IGNORE INTO question_tags (question_id, tag_id) VALUES (?, ?)'
            );
            $stmt->execute([$questionId, $tagId]);
        }
    }

    public function getByQuestion(int $questionId): array {
        $stmt = $this->db->prepare(
            'SELECT t.* FROM tags t
             JOIN question_tags qt ON t.id = qt.tag_id
             WHERE qt.question_id = ?
             ORDER BY t.name'
        );
        $stmt->execute([$questionId]);
        return $stmt->fetchAll();
    }

    public function getAll(): array {
        $stmt = $this->db->query(
            'SELECT t.*, COUNT(qt.question_id) AS question_count
             FROM tags t
             LEFT JOIN question_tags qt ON t.id = qt.tag_id
             GROUP BY t.id
             ORDER BY question_count DESC, t.name'
        );
        return $stmt->fetchAll();
    }

    public function findByName(string $name): array|false {
        $stmt = $this->db->prepare('SELECT * FROM tags WHERE name = ?');
        $stmt->execute([strtolower(trim($name))]);
        return $stmt->fetch();
    }
}
