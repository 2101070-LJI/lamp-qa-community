<?php
require_once __DIR__ . '/../config/database.php';

class Vote {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getUserVote(int $userId, string $targetType, int $targetId): array|false {
        $stmt = $this->db->prepare(
            'SELECT * FROM votes WHERE user_id = ? AND target_type = ? AND target_id = ?'
        );
        $stmt->execute([$userId, $targetType, $targetId]);
        return $stmt->fetch();
    }

    public function cast(int $userId, string $targetType, int $targetId, int $value): string {
        $existing = $this->getUserVote($userId, $targetType, $targetId);

        if ($existing) {
            if ((int)$existing['value'] === $value) {
                // 같은 방향 재투표 → 취소
                $this->db->prepare(
                    'DELETE FROM votes WHERE user_id = ? AND target_type = ? AND target_id = ?'
                )->execute([$userId, $targetType, $targetId]);
                $this->updateCount($targetType, $targetId, -$value);
                return 'cancelled';
            } else {
                // 반대 방향 → 변경
                $this->db->prepare(
                    'UPDATE votes SET value = ? WHERE user_id = ? AND target_type = ? AND target_id = ?'
                )->execute([$value, $userId, $targetType, $targetId]);
                $this->updateCount($targetType, $targetId, $value * 2);
                return 'changed';
            }
        }

        $this->db->prepare(
            'INSERT INTO votes (user_id, target_type, target_id, value) VALUES (?, ?, ?, ?)'
        )->execute([$userId, $targetType, $targetId, $value]);
        $this->updateCount($targetType, $targetId, $value);
        return 'added';
    }

    private function updateCount(string $targetType, int $targetId, int $delta): void {
        $table = $targetType === 'question' ? 'questions' : 'answers';
        $stmt  = $this->db->prepare(
            "UPDATE {$table} SET vote_count = vote_count + ? WHERE id = ?"
        );
        $stmt->execute([$delta, $targetId]);
    }

    public function getUserVotes(int $userId, string $targetType, array $targetIds): array {
        if (empty($targetIds)) return [];
        $placeholders = implode(',', array_fill(0, count($targetIds), '?'));
        $stmt = $this->db->prepare(
            "SELECT target_id, value FROM votes
             WHERE user_id = ? AND target_type = ? AND target_id IN ({$placeholders})"
        );
        $stmt->execute(array_merge([$userId, $targetType], $targetIds));
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['target_id']] = (int)$row['value'];
        }
        return $result;
    }
}
