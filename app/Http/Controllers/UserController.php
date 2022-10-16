<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Retrieve all the notifications for the user specified
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(User $user){
        return response()->json($user->unreadNotifications);
    }

    /**
     * Mark as seen the specified noitification
     *
     * @param User $user
     * @param string $id
     */
    public function markSeenNotification(User $user, string $id){
        $user->unreadNotifications->where('id', $id)->markAsRead();

        return response()->json(['success']);
    }
}
