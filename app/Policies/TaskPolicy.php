<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(?User $user, Task $task): bool
    {
        if ($user) {
            return $task->user_id === $user->id;
        }
        $guestId = session('guest_id');

        return $task->guest_id === $guestId;
    }

    public function update(?User $user, Task $task): bool
    {
        return $this->view($user, $task);
    }

    public function delete(?User $user, Task $task): bool
    {
        return $this->view($user, $task);
    }
}
