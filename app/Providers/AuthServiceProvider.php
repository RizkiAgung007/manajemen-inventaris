<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate untuk Superadmin: bisa melakukan segalanya, termasuk manajemen user.
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'superadmin';
        });

        // Gate untuk Admin & Superadmin: bisa mengelola konten (produk, kategori).
        Gate::define('manage-content', function (User $user) {
            return in_array($user->role, ['superadmin', 'admin']);
        });

        // Gate untuk User: hanya bisa membuat laporan (dan melihat data).
        Gate::define('create-reports', function (User $user) {
            return $user->role === 'user';
        });
    }
}
