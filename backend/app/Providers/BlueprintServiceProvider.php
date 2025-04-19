<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class BlueprintServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add point data type for PostgreSQL
        Blueprint::macro('point', function ($column_name) {
            return $this->addColumn('point', $column_name);
        });

        // Add other spatial macros if needed
        Blueprint::macro('spatialIndex', function ($column_name) {
            return $this->index($column_name, null, 'gist');
        });
    }
}
