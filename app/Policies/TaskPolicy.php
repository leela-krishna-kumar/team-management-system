<?php

namespace App\Policies;


use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Task $task)
    {
        return $user->hasRole('Admin') || $task->team->users->contains($user);
    }

    public function update(User $user, Task $task)
    {
        return $user->hasRole('Admin') || ($user->hasRole('Manager') && $task->team->users->contains($user));
    }

    public function delete(User $user, Task $task)
    {
        return $user->hasRole('Admin');
    }
}
