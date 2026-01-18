<?php

namespace App\Models;

use App\Models\Model;
use Core\Authenticator;
use Core\Log;
use Core\Password;

class Admin extends Model
{
  protected $table = "admins";
  
  /**
   * Attributes that should be hidden for arrays/JSON
   */
  protected $hidden = ['password', 'remember_token'];
  
  /**
   * Attributes that should be mass assignable
   */
  protected $fillable = [
    'name', 'email', 'password', 'email_verified_at', 'role', 'permissions'
  ];

  /**
   * Create a new admin with hashed password
   */
  public function createAdmin(array $data)
  {
    if (isset($data['password'])) {
      Log::info('Admin hashelés: ' . $data['email'], ['admin' => $data['email']], 'admin');
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    
    // Default role for admin if not specified
    if (!isset($data['role'])) {
      Log::info('Admin szerepkör beállítása: admin', ['admin' => $data['email']], 'admin');
      $data['role'] = 'admin';
    }
    
    return $this->create($data);
  }

  /**
   * Find admin by email
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
    $admin = $this->findByEmail($email);
    return !empty($admin);
  }

  /**
   * Verify admin's password
   */
  public function verifyPassword(string $email, string $password)
  {
    $auth = new Authenticator();
    return $auth->checkPassword($email, $password, $this->table);
  }

  /**
   * Admin login attempt
   */
  public function attemptLogin(string $email, string $password, bool $verified = false)
  {
    $auth = new Authenticator();
    return $auth->attempt($email, $password, $this->table, $verified);
  }

  /**
   * Mark email as verified
   */
  public function verifyEmail($adminId)
  {
    return $this->updateById($adminId, [
      'email_verified_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Check if admin's email is verified
   */
  public function isEmailVerified($adminId): bool
  {
    $admin = $this->find($adminId);
    return $admin && !empty($admin->email_verified_at);
  }

  /**
   * Get all verified admins
   */
  public function getVerifiedAdmins()
  {
    return $this->raw("SELECT * FROM {$this->table} WHERE email_verified_at IS NOT NULL");
  }

  /**
   * Get all unverified admins
   */
  public function getUnverifiedAdmins()
  {
    return $this->raw("SELECT * FROM {$this->table} WHERE email_verified_at IS NULL");
  }

  /**
   * Update admin password
   */
  public function updatePassword($adminId, string $newPassword)
  {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    return $this->updateById($adminId, ['password' => $hashedPassword]);
  }

  /**
   * Generate and update password
   */
  public function generatePassword($adminId, int $length = 16)
  {
    $newPassword = Password::generate($length);
    $this->updatePassword($adminId, $newPassword);
    return $newPassword; // Return plain password for email/display
  }

  /**
   * Get admins by role
   */
  public function getAdminsByRole(string $role)
  {
    return $this->findBy('role', $role);
  }

  /**
   * Check if admin has specific role
   */
  public function hasRole($adminId, string $role): bool
  {
    $admin = $this->find($adminId);
    return $admin && $admin->role === $role;
  }

  /**
   * Check if admin is super admin
   */
  public function isSuperAdmin($adminId): bool
  {
    return $this->hasRole($adminId, 'super_admin');
  }

  /**
   * Get admin's full name
   */
  public function getFullName($adminId): string
  {
    $admin = $this->find($adminId);
    if (!$admin) return '';
    
    // If you have separate first_name and last_name columns:
    // return trim($admin->first_name . ' ' . $admin->last_name);
    
    // If you have single name column:
    return $admin->name ?? '';
  }

  /**
   * Get active admins (not soft deleted and verified)
   */
  public function getActiveAdmins()
  {
    return $this->raw("SELECT * FROM {$this->table} WHERE deleted_at IS NULL AND email_verified_at IS NOT NULL");
  }

  /**
   * Search admins by name or email
   */
  public function searchAdmins(string $searchTerm)
  {
    $term = '%' . $searchTerm . '%';
    return $this->raw(
      "SELECT * FROM {$this->table} WHERE (name LIKE :term OR email LIKE :term) AND deleted_at IS NULL",
      ['term' => $term]
    );
  }

  /**
   * Get recent registered admins
   */
  public function getRecentAdmins(int $limit = 10)
  {
    return $this->raw("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT {$limit}");
  }

  /**
   * Set admin permissions (if using JSON permissions column)
   */
  public function setPermissions($adminId, array $permissions)
  {
    $permissionsJson = json_encode($permissions);
    return $this->updateById($adminId, ['permissions' => $permissionsJson]);
  }

  /**
   * Get admin permissions (if using JSON permissions column)
   */
  public function getPermissions($adminId): array
  {
    $admin = $this->find($adminId);
    if (!$admin || empty($admin->permissions)) {
      return [];
    }
    
    $permissions = json_decode($admin->permissions, true);
    return is_array($permissions) ? $permissions : [];
  }

  /**
   * Check if admin has specific permission
   */
  public function hasPermission($adminId, string $permission): bool
  {
    $permissions = $this->getPermissions($adminId);
    return in_array($permission, $permissions);
  }

  /**
   * Add permission to admin
   */
  public function addPermission($adminId, string $permission)
  {
    $permissions = $this->getPermissions($adminId);
    if (!in_array($permission, $permissions)) {
      $permissions[] = $permission;
      $this->setPermissions($adminId, $permissions);
    }
    return true;
  }

  /**
   * Remove permission from admin
   */
  public function removePermission($adminId, string $permission)
  {
    $permissions = $this->getPermissions($adminId);
    $permissions = array_filter($permissions, fn($p) => $p !== $permission);
    $this->setPermissions($adminId, array_values($permissions));
    return true;
  }

  /**
   * Get all super admins
   */
  public function getSuperAdmins()
  {
    return $this->findBy('role', 'super_admin');
  }

  /**
   * Promote admin to super admin
   */
  public function promoteToSuperAdmin($adminId)
  {
    return $this->updateById($adminId, ['role' => 'super_admin']);
  }

  /**
   * Demote super admin to regular admin
   */
  public function demoteFromSuperAdmin($adminId)
  {
    return $this->updateById($adminId, ['role' => 'admin']);
  }

  /**
   * Get admin activity log (if you have an activity_log table)
   */
  public function getActivityLog($adminId, int $limit = 50)
  {
    // This assumes you have an activity_log table with admin_id foreign key
    return $this->raw(
      "SELECT * FROM activity_log WHERE admin_id = :admin_id ORDER BY created_at DESC LIMIT {$limit}",
      ['admin_id' => $adminId]
    );
  }

  /**
   * Update last login time
   */
  public function updateLastLogin($adminId)
  {
    return $this->updateById($adminId, [
      'last_login_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Check if admin account is locked (if you have account locking feature)
   */
  public function isAccountLocked($adminId): bool
  {
    $admin = $this->find($adminId);
    return $admin && !empty($admin->locked_at);
  }

  /**
   * Lock admin account
   */
  public function lockAccount($adminId, string $reason = '')
  {
    return $this->updateById($adminId, [
      'locked_at' => date('Y-m-d H:i:s'),
      'lock_reason' => $reason
    ]);
  }

  /**
   * Unlock admin account
   */
  public function unlockAccount($adminId)
  {
    return $this->updateById($adminId, [
      'locked_at' => null,
      'lock_reason' => null
    ]);
  }
}
