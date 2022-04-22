<?php

namespace Modules\Kost\Providers;

use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;

class CapabilityServiceProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Modules\Kost\Entities\Kost::class => Modules\Kost\Policies\KostPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('kost.store', \Modules\Kost\Policies\KostPolicy::class.'@store');
        Gate::define('kost.update', \Modules\Kost\Policies\KostPolicy::class.'@update');
        Gate::define('kost.destroy', \Modules\Kost\Policies\KostPolicy::class.'@destroy');
    }
}