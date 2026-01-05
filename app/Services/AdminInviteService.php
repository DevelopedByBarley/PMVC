<?php

namespace App\Services;

use PDO;
use RuntimeException;
use App\Traits\GeneratesTokens;
use Core\Database;
use Exception;

class AdminInviteService
{
    use GeneratesTokens;

    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Meghívás létrehozása
     */
    public function createInvite($admin, $adminId, $ttlHours = 24)
    {
        $token = $this->generateToken();
        $expiresAt = date('Y-m-d H:i:s', time() + ($ttlHours * 3600));

        $this->db->query("
            INSERT INTO admin_invites (admin_id, email, token, expires_at)
            VALUES (:admin_id, :email, :token, :expires_at)
        ", [
            'admin_id' => $adminId,
            'email' => $admin['email'],
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        return $token;
    }

    /**
     * Meghívás ellenőrzése
     */
    public function validateInvite($token)
    {
        $invite = $this->db->query("
            SELECT *
            FROM admin_invites
            WHERE token = :token
              AND expires_at > NOW()
              AND accepted_at IS NULL
        ", ['token' => $token])->find(PDO::FETCH_ASSOC);

        if (!$invite) {
            abort(404, 'Érvénytelen vagy lejárt meghívó token.');
        }

        return $invite;
    }

    /**
     * Meghívás elfogadása → admin létrehozás
     */
    public function acceptInvite($token, $name, $password, $role = 'admin')
    {
        if (strlen($password) < 8) {
            throw new RuntimeException('A jelszó túl rövid.');
        }

        if (!in_array($role, ['admin', 'editor'], true)) {
            throw new RuntimeException('Érvénytelen role.');
        }

        $this->db->connection->beginTransaction();

        try {
            // invite lock
            $invite = $this->db->query("
                SELECT *
                FROM admin_invites
                WHERE token = :token
                  AND expires_at > NOW()
                  AND accepted_at IS NULL
            ", ['token' => $token])->find(PDO::FETCH_ASSOC);

            if (!$invite) {
                $this->db->connection->rollBack();
                throw new RuntimeException('A meghívás nem érvényes.');
            }

            // admin létrehozása
            $this->db->query("
                INSERT INTO admins (name, email, password, role, created_at, updated_at)
                VALUES (:name, :email, :password, :role, NOW(), NOW())
            ", [
                'name' => $name,
                'email' => $invite['email'],
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role,
            ]);

            // invite lezárása
            $this->db->query("
                UPDATE admin_invites
                SET accepted_at = NOW()
                WHERE id = :id
            ", ['id' => $invite['id']]);

            $this->db->connection->commit();
        } catch (Exception $e) {
            $this->db->connection->rollBack();
            throw $e;
        }
    }


    public function deleteToken($token)
    {
        $this->db->query("
            DELETE FROM admin_invites
            WHERE token = :token
        ", ['token' => $token]);
    }



}
