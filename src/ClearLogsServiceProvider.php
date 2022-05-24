<?php

namespace Frugone\ClearLogs;

use Illuminate\Support\ServiceProvider;

class ClearLogsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([ClearLogs::class]);
    }
}
