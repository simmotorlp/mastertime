<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\PostgresGrammar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class PostgresExtensionServiceProvider extends ServiceProvider
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
        // Add macro for PostgreSQL point data type
        PostgresGrammar::macro('typePoint', function () {
            return 'point';
        });

        // Add macro to the Blueprint class to add point columns
        Blueprint::macro('point', function ($column) {
            return $this->addColumn('point', $column);
        });

        // Helper function to set a point value
        DB::macro('asPoint', function ($lat, $lng) {
            return DB::raw("ST_MakePoint($lng, $lat)");
        });

        // Helper function to get latitude from point
        DB::macro('getLat', function ($column) {
            return DB::raw("ST_Y($column)");
        });

        // Helper function to get longitude from point
        DB::macro('getLng', function ($column) {
            return DB::raw("ST_X($column)");
        });

        // Helper function for location-based queries
        DB::macro('distanceBetween', function ($point1, $point2) {
            return DB::raw("ST_Distance($point1, $point2)");
        });

        // Create the PostGIS extension if it doesn't exist
        if (DB::connection()->getDriverName() === 'pgsql') {
            try {
                $exists = DB::select("SELECT 1 FROM pg_extension WHERE extname = 'postgis'");
                if (empty($exists)) {
                    DB::statement('CREATE EXTENSION IF NOT EXISTS postgis');
                }
            } catch (\Exception $e) {
                // The extension might not be available in development
                // We'll log this but continue
                \Log::warning('Could not create PostGIS extension: ' . $e->getMessage());
            }
        }
    }
}
