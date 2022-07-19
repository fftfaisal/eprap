<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthGates {
    public function handle( $request, Closure $next ) {
        $user = Auth::user();
        if ( $user ) {
            // off for developement
            $exclude_routes = [
                // 'admin.home',
                'admin.get_started',
                'admin.interview_schedule_times',
                'admin.store_verification_data',
                'admin.profile',
                // 'admin.tutor.profile',
                // 'admin.tutor.profile.update',
                // 'admin.tutor.profile.upload_image',
                // 'admin.profile.change.password',
                // 'admin.tutor.profile.verification',
                // 'admin.tutor.profile.view',
                // 'admin.tutor.upload_image',
            ];
            if ( $user->role_id != 1 && $request->routeIs('admin.*') && ! in_array($request->route()->getName(), $exclude_routes) && !$user->is_verified  && in_array($user->role_id, [3,5]) ) {
                if ( $request->wantsJson() ) {
                    return response()->json( ['message' => 'Profile is not active'], 403 );
                }
                return response()->view('errors.unverified');
            }

        }

        return $next( $request );
    }

}
