<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{

	const LOG_FILE_EXTENSION = 'log';
	const LOG_FILE_NAME = 'laravel';

	protected $logFilePath = '';

	/**
	 * The console command name.
	 */
	protected $name = 'log:clear';


	/**
	 * Number of days to preserve logs
	 * @var Int
	 */
	protected $days=7;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'log:clear';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Removes and truncates old logs. supported "single" and "daily".';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$logType = config('logging.default');
		$datePreserve = $this->getDatePreserveLogs($this->days);

		$this->logFilePath = dirname( config('logging.channels.'.$logType.'.path'));

		if( $logType == 'single'){
			$this->singleLogsClear($datePreserve);
		}elseif($logType == 'daily'){
			$this->dailyLogsClear($datePreserve);
		}else{
			$this->info( '"'.$logType. '" log not supported. Only "single" and "daily" supported ');
		}
	}


	/**
	 * Deletes log files prior to the specified date
	 * @param Carbon $date
	 */
	private function dailyLogsClear($date)
	{
		$files = $this->getLogFiles($date);
		foreach( $files as $file ){
			$this->deleteFile($file);
		}
	}

	/**
	 * Deletes log records prior to the specified date
	 * @param Carbon $date
	 */
	private function singleLogsClear($dateKeep)
	{
		//$line = $this->getLineToPreserve($date);

		$logFile =  $this->logFilePath.'/'.self::LOG_FILE_NAME.'.'.self::LOG_FILE_EXTENSION;

		$lines = file($logFile);
		foreach($lines as $lineNumber=>$line){
			$date = substr($line, 1, 10 );
			if( $this->isDateFormat($date)){
				$date = Carbon::createFromFormat('Y-m-d', $date);
				if( $date >= $dateKeep ){
					if( $lineNumber > 0 ){
						file_put_contents($logFile,  implode(PHP_EOL, array_slice($lines, $lineNumber) ) );
					}
					break;
				}
			}
		}
	}


	/**
	 * Gets the date from which logs are to be preserved
	 * @param Int $days  cantidad de dias a conservar
	 */
	private function getDatePreserveLogs($days)
	{
		return Carbon::now()->subDays($days)->startOfDay();
	}

	/**
	 * Determines if the string is a valid date
	 */
	private function isDateFormat($value)
	{
		return preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$value);
	}


	/**
	 * Return all log files after the indicated date
	 *
	 * @param Carbon $dateKeep
	 * @return Collection
	 */
	protected function getLogFiles($dateKeep)
	{
		$files = File::files($this->logFilePath);
		$logFiles = collect([]);
		foreach ($files as $logFile) {
			if( $logFile->getExtension() == self::LOG_FILE_EXTENSION ){
				$date = Carbon::createFromTimestamp( $logFile->getMTime());
				//$date = $this->getDateByName( $logFile->getFilename());
				if( $date && $date < $dateKeep ){
					$logFiles->put($date->format('Y-m-d'), $logFile);
				}
			}
		}
		return $logFiles;
	}

	/**
	 * Gets the date from the name of the file
	 *
	 * @param String $fileName
	 * @return Carbon/Carbon or boolean false
	 */
	private function getDateByName($fileName){
		$date = str_replace( [self::LOG_FILE_NAME.'-', '.'.self::LOG_FILE_EXTENSION ], '', $fileName );
		return ($this->isDateFormat($date))? Carbon::createFromFormat( 'Y-m-d',$date):false;
	}

	/**
	 * Deletes the specified file
	 * Symfony\Component\Finder\SplFileInfo
	 */
	private function deleteFile($file)
	{
		$filePath = $file->getRealPath();
		if ( file_exists($filePath) && is_file($filePath)) {
			unlink($filePath);
			$this->info( 'Deleted ' . basename($filePath)  );
		}else{
			$this->info( 'File ' . basename($filePath) . ' could not be found');
		}
	}

}
