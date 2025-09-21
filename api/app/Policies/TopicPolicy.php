<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;

class TopicPolicy
{
    public function viewAny(User $user)
    {
        return $user->can('view', $user);
    }

    public function view(User $user, Topic $topic)
    {
        return $user->can('view', $topic->course);
    }

    public function create(User $user, Topic $topic)
    {
        return $user->can('update', $topic->course);
    }

    public function update(User $user, Topic $topic)
    {
        return $user->can('update', $topic->course);
    }

    public function delete(User $user, Topic $topic)
    {
        return $user->can('delete', $topic->course);
    }

    public function restore(User $user, Topic $topic)
    {
        return $user->can('update', $topic->course);
    }

    public function forceDelete(User $user, Topic $topic)
    {
        return $user->can('delete', $topic->course);
    }
}
