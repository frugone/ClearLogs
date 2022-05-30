<?php

namespace Frugone\ClearLogs\Tests;

use Frugone\ClearLogs\Console\Commands\ClearLogs;
use Config;

class ClearLogsTest extends TestCase
{
    /**
     * @test
     */
    public function singleLogtest()
    {
        $numberOfLogs = 10;
        $daysToPreserveLogs = 7;

        Config::set('logging.default', 'single');
        Config::set('clearlogs.days', $daysToPreserveLogs );

        $periodRange = $this->createSingleLog($numberOfLogs);

        $this->artisan('log:clear');

        $logFilePath = $this->logPath . $this->getSingleLogName();
        $lines = file($logFilePath);

        $this->assertTrue(count($lines) == $daysToPreserveLogs);

        foreach ($periodRange as $key => $date ) {
            $logLine = $this->getFakeLineLogByDate($date);
            if ($key < ($numberOfLogs - $daysToPreserveLogs )) {
                $this->assertTrue(in_array($lines, $logLine));
            } else {
                $this->assertFalse(in_array($lines, $logLine));
            }
        }
    }

    /**
     * @test
     */
    public function dailyLogTest()
    {
        $numberOfLogs = 10;
        $daysToPreserveLogs = 7;
        Config::set('logging.default', 'daily');
        Config::set('clearlogs.days', $daysToPreserveLogs );

        $periodRange = $this->createDailyLogs($numberOfLogs);

        $this->artisan('log:clear');

        foreach ($periodRange as $key => $date ) {
            $logFile = $this->logPath . $this->getLogNameByDate($date);
            if ($key < ($numberOfLogs - $daysToPreserveLogs )) {
                $this->assertFileDoesNotExist($logFile);
            } else {
                $this->assertFileExists($logFile);
            }
        }
    }
}
