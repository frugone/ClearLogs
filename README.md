# ClearLogs
Laravel comand 

Command to remove old logs from Laravel. Supports "single" and "daily" channels

## Installation
``` sh
composer require pfrug/clear-logs
```

Add the service provider in `config/app.php`
```php
'providers' => [
    ...
    Pfrug\ClearLogs\ClearLogsServiceProvider::class,
];
```
Add the configuration file (optionally)
``` sh
php artisan vendor:publish --provider="Pfrug\ClearLogs\ClearLogsServiceProvider"
```
Optionally, you may also run php artisan vendor:publish --tag="clear-logs-config" to publish the configuration file in config/clearlogs.php 

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

## Configuration

Pblish configuration

```sh
php artisan vendor:publish --tag="clear-logs-config"
```

This command create file configuration options to: `config/clearlogs.php`
```php
/*
 * Number of days to preserve logs
 * @var int
 */
'days' => 7,

/**
 * Indicates the criteria to evaluate the date of the log file to be deleted.
 * it can be by date of modification or by name of the file ej:"laravel-2022-05-22.log"
 * @var String mit|name
 */
'evalDateByNameOrMTime' => 'name',
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

