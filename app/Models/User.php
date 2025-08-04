<?php

namespace App\Models;

use App\Models\Model;
use Core\Authenticator;
use Core\Password;

class User extends Model
{
  protected $table = "users";
  
  /**
   * Attributes that should be hidden for arrays/JSON
   */
  protected $hidden = ['password', 'remember_token'];
  
  /**
   * Attributes that should be mass assignable
   */
  protected $fillable = [
    'name', 'email', 'password', 'email_verified_at', 'role'
  ];

  /**
   * Create a new user with hashed password
   */
  public function createUser(array $data)
  {
    if (isset($data['password'])) {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    
    return $this->create($data);
  }

  /**
   * Find user by email
   */
  public function findByEmail(string $email)
  {
    return $this->findOneBy('email', $email);
  }

  /**
   * Check if email already exists
   */
  public function emailExists(string $email): bool
  {
    $user = $this->findByEmail($email);
    return !empty($user);
  }

  /**
   * Verify user's password
   */
  public function verifyPassword(string $email, string $password)
  {
    $auth = new Authenticator();
    return $auth->checkPassword($email, $password, $this->table);
  }

  /**
   * Login attempt
   */
  public function attemptLogin(string $email, string $password, bool $verified = false)
  {
    $auth = new Authenticator();
    return $auth->attempt($email, $password, $this->table, $verified);
  }

  /**
   * Mark email as verified
   */
  public function verifyEmail($userId)
  {
    return $this->updateById($userId, [
      'email_verified_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Check if user's email is verified
   */
  public function isEmailVerified($userId): bool
  {
    $user = $this->find($userId);
    return $user && !empty($user->email_verified_at);
  }

  /**
   * Get all verified users
   */
  public function getVerifiedUsers()
  {
    return $this->raw("SELECT * FROM {$this->table} WHERE email_verified_at IS NOT NULL");
  }

  /**
   * Get all unverified users
   */
  public function getUnverifiedUsers()
  {
    return $this->raw("SELECT * FROM {$this->table} WHERE email_verified_at IS NULL");
  }

  /**
   * Update user password
   */
  public function updatePassword($userId, string $newPassword)
  {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    return $this->updateById($userId, ['password' => $hashedPassword]);
  }

  /**
   * Generate and update password
   */
  public function generatePassword($userId, int $length = 12)
  {
    $newPassword = Password::generate($length);
    $this->updatePassword($userId, $newPassword);
    return $newPassword; // Return plain password for email/display
  }

  /**
   * Get users by role
   */
  public function getUsersByRole(string $role)
  {
    return $this->findBy('role', $role);
  }

  /**
   * Check if user has specific role
   */
  public function hasRole($userId, string $role): bool
  {
    $user = $this->find($userId);
    return $user && $user->role === $role;
  }

  /**
   * Get user's full name (if you have first_name, last_name columns)
   * Or just return name if it's a single column
   */
  public function getFullName($userId): string
  {
    $user = $this->find($userId);
    if (!$user) return '';
    
    // If you have separate first_name and last_name columns:
    // return trim($user->first_name . ' ' . $user->last_name);
    
    // If you have single name column:
    return $user->name ?? '';
  }

  /**
   * Get active users (not soft deleted and verified)
   */
  public function getActiveUsers()
  {
    return $this->raw("SELECT * FROM {$this->table} WHERE deleted_at IS NULL AND email_verified_at IS NOT NULL");
  }

  /**
   * Search users by name or email
   */
  public function searchUsers(string $searchTerm)
  {
    $term = '%' . $searchTerm . '%';
    return $this->raw(
      "SELECT * FROM {$this->table} WHERE (name LIKE :term OR email LIKE :term) AND deleted_at IS NULL",
      ['term' => $term]
    );
  }

  /**
   * Get recent registered users
   */
  public function getRecentUsers(int $limit = 10)
  {
    return $this->raw("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT {$limit}");
  }
}
