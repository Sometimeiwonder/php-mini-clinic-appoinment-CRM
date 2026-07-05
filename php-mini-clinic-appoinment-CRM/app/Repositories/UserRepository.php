<?php

class UserRepository
{
    public function __construct(private PDO $db) {}

    public function findActiveByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id, name, email, password_hash, role, status
             FROM users WHERE email = :email AND status = 'active' LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name, email, role FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findByRememberToken(string $token): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id, name, email, role FROM users
             WHERE remember_token = :token AND remember_expiry > NOW() AND status = 'active' LIMIT 1"
        );
        $stmt->execute(['token' => $token]);
        return $stmt->fetch() ?: null;
    }

    public function setRememberToken(int $userId, string $token): void
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET remember_token = :token, remember_expiry = DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE id = :id"
        );
        $stmt->execute(['token' => $token, 'id' => $userId]);
    }

    public function clearRememberToken(int $userId): void
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET remember_token = NULL, remember_expiry = NULL WHERE id = :id"
        );
        $stmt->execute(['id' => $userId]);
    }
}
