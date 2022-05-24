# ClearLogs
Laravel comand 

Command to remove old logs from Laravel. Supports "single" and "daily" channels

## Installation
``` sh
composer require frugone/clear-logs
```

Add the service provider in `config/app.php`
```php
'providers' => [
    ...
    Frugone\ClearLogs\ClearLogsServiceProvider::class,
];
```
Add the configuration file (optionally)
``` sh
php artisan vendor:publish --tag="clear-logs-config"
```

## Usage
Execute artisan command:
``` sh
php artisan log:clear
``` 

Or run as a schedule.  Add this code in `app/Console/Kernel.php`

``` php
protected function schedule(Schedule $schedule)
{
    $schedule->command('log:clear')->daily();
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

