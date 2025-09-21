<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;
use App\Models\Topic;

class LessonPolicy
{
    public function viewAny(User $user, Topic $topic)
    {
        return $user->can('view', $topic->course);
    }

    public function view(User $user, Lesson $lesson)
    {
        return $user->can('view', $lesson->topic->course);
    }

    public function create(User $user, Topic $topic)
    {
        return $user->can('view', $topic->course);
    }

    public function update(User $user, Lesson $lesson)
    {
        return $user->can('view', $lesson->topic->course);
    }

    public function delete(User $user, Lesson $lesson)
    {
        return $user->can('view', $lesson->topic->course);
    }

    public function restore(User $user, Lesson $lesson)
    {
        return $user->can('view', $lesson->topic->course);
    }

    public function forceDelete(User $user, Lesson $lesson)
    {
        return $user->can('view', $lesson->topic->course);
    }
}
