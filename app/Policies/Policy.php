<?php

namespace App\Policies;

class Policy
{
    /**
     * Authorize a user action based on the policy.
     *
     * @param string $action
     * @param mixed $user
     * @param mixed $model
     * @return bool
     */
    
    public function authorize($action, $user, $model)
    {
        if (method_exists($this, $action)) {
            return $this->$action($user, $model);
        }
        return false;
    }
}
