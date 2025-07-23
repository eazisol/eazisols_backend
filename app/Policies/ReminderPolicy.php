<?php

namespace App\Policies;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReminderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any reminders.
     */
    public function viewAny(User $user)
    {
        return true; // All authenticated users can view their own reminders
    }

    /**
     * Determine whether the user can view the reminder.
     */
    public function view(User $user, Reminder $reminder)
    {
        return $user->id === $reminder->user_id;
    }

    /**
     * Determine whether the user can create reminders.
     */
    public function create(User $user)
    {
        return true; // All authenticated users can create reminders
    }

    /**
     * Determine whether the user can update the reminder.
     */
    public function update(User $user, Reminder $reminder)
    {
        return $user->id === $reminder->user_id;
    }

    /**
     * Determine whether the user can delete the reminder.
     */
    public function delete(User $user, Reminder $reminder)
    {
        return $user->id === $reminder->user_id;
    }
}