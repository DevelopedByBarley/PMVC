<?php

namespace App\Policies;

class UserPolicy extends Policy
{
    /**
     * Determine if the user can view the user profile.
     */
    public function view($user, $model)
    {
        // User can view their own profile
        return $user->id === $model->id;
    }

    /**
     * Determine if the user can update the user profile.
     */
    public function update($user_id, $model_id)
    {
        return $user_id === $model_id;
    }

    /**
     * Determine if the user can delete the user profile.
     */
    public function delete($user, $model)
    {
        // User can delete their own profile (or admin can delete any)
        return $user->id === $model->id || $user->role === 'admin';
    }

    /**
     * Determine if the user can create a new user profile.
     */
    public function create($user)
    {
        // Only admins can create new users
        return $user->role === 'admin' || $user->role === 'super_admin';
    }

    /**
     * Determine if the user can view any user profiles (admin function).
     */
    public function viewAny($user)
    {
        // Only admins can view all users
        return $user->role === 'admin' || $user->role === 'super_admin';
    }
}
