<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check() && Auth::user()) {
            User::where('id', Auth::id())->update(['last_seen' => Carbon::now()->timezone('GMT+1')]);  

            return $next($request);
        }
        return $next($request);

    }
}
