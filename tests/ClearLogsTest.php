<?php

namespace Pfrug\ClearLogs\Tests;

use Pfrug\ClearLogs\Console\Commands\ClearLogs;
use Config;

class ClearLogsTest extends TestCase
{
    /**
     * @test
     * @covers ClearLogs
     */
    public function singleLogtest()
    {
        $numberOfLogs = 10;
        $daysToPreserveLogs = 7;

        Config::set('logging.default', 'single');
        Config::set('clearlogs.days', $daysToPreserveLogs);

        $periodRange = $this->createSingleLog($numberOfLogs);

        $this->artisan('log:clear');

        $logFilePath = $this->logPath . $this->getSingleLogName();
        $lines = file($logFilePath, FILE_IGNORE_NEW_LINES);

        $this->assertTrue(count($lines) == $daysToPreserveLogs);

        foreach ($periodRange as $key => $date) {
            $logLine = $this->getFakeLineLogByDate($date);
            if ($key < ($numberOfLogs - $daysToPreserveLogs)) {
                $this->assertFalse(in_array($logLine, $lines));
            } else {
                $this->assertTrue(in_array($logLine, $lines));
            }
        }
    }

    /**
     * @test
     * @covers ClearLogs
     */
    public function dailyLogTest()
    {
        $numberOfLogs = 10;
        $daysToPreserveLogs = 7;
        Config::set('logging.default', 'daily');
        Config::set('clearlogs.days', $daysToPreserveLogs);

        $periodRange = $this->createDailyLogs($numberOfLogs);

        $this->artisan('log:clear');

        foreach ($periodRange as $key => $date) {
            $logFile = $this->logPath . $this->getLogNameByDate($date);
            if ($key < ($numberOfLogs - $daysToPreserveLogs )) {
                $this->assertFileDoesNotExist($logFile);
            } else {
                $this->assertFileExists($logFile);
            }
        }
    }
}
