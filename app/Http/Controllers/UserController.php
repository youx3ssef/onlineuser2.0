<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class UserController extends Controller
{
    public function onlineUsers()
    {
        $now = Carbon::now();
        $users = User::orderByDesc('last_seen')->get()->map(function ($user) use ($now) {
            $user->is_online = $user->last_seen && $now->diffInMinutes(Carbon::parse($user->last_seen)) <= 1;
            return $user;
        });

        return view('online_users', compact('users'));
    }

    public function fetchOnlineUsers()
    {
        $now = Carbon::now();
        $users = User::orderByDesc('last_seen')->get()->map(function ($user) use ($now) {
            $user->is_online = $user->last_seen && $now->diffInMinutes(Carbon::parse($user->last_seen)) <= 1;
            return $user;
        });

        return response()->json($users);
    }
}
