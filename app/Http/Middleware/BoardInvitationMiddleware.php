<?php

namespace App\Http\Middleware;

use App\Models\BoardMember;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BoardInvitationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // $boardId = $request->route('board_id');
        // fix

        $boardId = 1;

        $isInvited = BoardMember::where('user_id', $user->id)
            ->where('board_id', $boardId)
            ->exists();

        if (!$isInvited) {
            abort(403, 'Bạn không có quyền truy cập vào board này.');
        }

        return $next($request);
    }
}
