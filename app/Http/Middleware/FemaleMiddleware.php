<?php

namespace App\Http\Middleware;

use App\UserProfile;
use Closure;

class FemaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try{
            $profile = UserProfile::where('user_id', $request->route('user_id'))->firstOrFail();
            if($profile->gender != UserProfile::FEMALE)
            {
                $data = [
                    'code' => 403,
                    'message' => 'Only female is allowed to access',
                    'data' => []
                ];
                return response()->json($data, 403);
            }
        }catch(Exception $e)
        {
            Log::error($e);
            $data = [
                'code' => 500,
                'message' => 'Internal Server Error',
                'data' => []
            ];
            return response()->json($data, 500);
        }
        return $next($request);
    }
}