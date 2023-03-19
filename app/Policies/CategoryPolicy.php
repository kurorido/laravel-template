<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Category $category,)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Category $category,)
    {
        return true;
    }

    public function delete(User $user, Category $category,)
    {
        return true;
    }
}
