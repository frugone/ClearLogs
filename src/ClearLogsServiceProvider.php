<?php

namespace Frugone\ClearLogs;

use Illuminate\Support\ServiceProvider;
use Frugone\ClearLogs\Console\Commands\ClearLogs;

class ClearLogsServiceProvider extends ServiceProvider
{

    public function boot()
    {
         $this->publishes(
            [
                __DIR__ . '/../config/clearlogs.php' => config_path('clearlogs.php'),
            ]
        );
    }

    public function register(): void
    {
        $this->commands([ClearLogs::class]);

        $this->mergeConfigFrom(
                __DIR__.'/../config/clearlogs.php', 'clearlogs'
        );
    }


}
