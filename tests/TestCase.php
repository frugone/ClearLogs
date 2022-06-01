<?php

namespace Frugone\ClearLogs\Tests;

use Frugone\ClearLogs\ClearLogsServiceProvider;
//use Illuminate\Foundation\Application;
use Carbon\Carbon;
use DateTime;
use DatePeriod;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * The log path
     */
    protected $logPath;

    public function setUp(): void
    {
        parent::setUp();
        $this->logPath = storage_path('logs') . '/';
        $this->deleteAllLogs();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteAllLogs();
    }

    /**
     * Load the command service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [ClearLogsServiceProvider::class];
    }

    /**
     * Create single log file for the number of days specified
     * @param int $days
     * @return DatePeriod
     */
    protected function createSingleLog(int $days): DatePeriod
    {
        $logFilePath = $this->logPath . $this->getSingleLogName();
        $period = $this->getPeriodRange($days);
        foreach ($period as $date) {
            $lines[] = $this->getFakeLineLogByDate($date);
        }

        file_put_contents($logFilePath, implode(PHP_EOL, $lines));
        return $period;
    }

    /**
     * Create log files for the number of days specified
     * @param int $days
     * @return DatePeriod
     */
    protected function createDailyLogs(int $days, $fakeTime = true): DatePeriod
    {
        $period = $this->getPeriodRange($days);
        foreach ($period as $date) {
            $logFileName = $this->getLogNameByDate($date);
            $time = ($fakeTime) ? time() : $date->time();
            $this->addLog($logFileName, $time);
        }
        return $period;
    }

    /**
     * Create a time period for the number of days indicated
     * @param int $days
     * @return DatePeriod
     */
    protected function getPeriodRange(int $days): DatePeriod
    {
        return new DatePeriod(
            (new DateTime('NOW'))->modify('- ' . ($days - 2) . ' day'),
            new \DateInterval('P1D'),
            (new DateTime('NOW'))->modify('+ 2 day'),
        );
    }

    /**
     * Add file log
     * @param String|Array $files
     * @param int $time timestamp
     */
    protected function addLog($files, $time): void
    {
        foreach ((array) $files as $file) {
            touch($this->logPath . $file, $time);
        }
    }

    /**
     * Delete a specific log files
     * @param String|Array $files
     */
    private function deleteLog($files): void
    {
        foreach ((array) $files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Delete all fake log files int the test temporary directory.
     */
    private function deleteAllLogs(): void
    {
        //$this->deleteLog(glob($this->logPath . '*'));
    }

    /**
     * get file log name, by date
     * @param DateTime $date
     * @return String
     */
    protected function getLogNameByDate(DateTime $date): string
    {
        return 'laravel-' . $date->format('Y-m-d') . '.log';
    }

    /**
     * @return String
     */
    protected function getSingleLogName(): string
    {
        return 'laravel.log';
    }

    /**
     * get fake string log by date
     * @param DateTime $date
     * @return String
     */
    protected function getFakeLineLogByDate(DateTime $date): string
    {
        return  '[' . $date->format('Y-m-d H:i:s') . '] fake log ' . $date->format('D');
    }
}
