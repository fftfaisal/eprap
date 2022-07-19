<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();
        // admin bypass all permission check
        Gate::before(function ($user) {
            if ($user->role_id == 1) {
                return true;
            }
        });
        // try {
        //     Permission::with('roles')->get()->map(function ($permission) {
        //         Gate::define($permission->name, function ($user) use ($permission) {
        //             return $user->hasPermissionTo($permission);
        //         });
        //     });
        // } catch (\Exception $e) {
        //     report($e);
        //     return false;
        // }
    }
}
