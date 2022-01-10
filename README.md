# ClearLogs
Laravel comand 

Comando para eliminar logs antiguos de Laravel. Soporta canales "single" y "daily" 

## Uso
Se puede ejecutar como comando de artisan `php artisan log:clear`

O se puede agregar como tarea (schedule) agregando el comando en `app/Console/Kernel.php`

``` php
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('log:clear')->daily();
    }
```
