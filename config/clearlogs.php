<?php

return [

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
];