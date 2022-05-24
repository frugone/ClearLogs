<?php

namespace Frugone\ClearLogs;

use Illuminate\Support\ServiceProvider;

class ClearLogsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([ClearLogs::class]);
        $this->loadConfig();
    }

    private function loadConfig(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../config/clearlogs.php' => config_path('clearlogs.php'),
            ],
            'clear-logs-config'
        );
    }
}
