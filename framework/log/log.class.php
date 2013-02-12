<?php

/**
 * 	Log
 * 	
 * 	Public methods:
 * 	@method error (string : text, string : unit);
 *  @method success (string : text, string : unit);
 *  @method notice (string : text, string : unit);
 *  @method save (string : path);
 *
 *	Private methods:
 *  @method write (string : text, string : unit, string : level);
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */

class Log {
	
	private static $lines = array();
	
	
	/**
	 * 	@name write
	 * 	Function wirte log line into private array.
	 * 
	 * 	@param text : string
	 * 	@param unit : string
	 * 	@param level : string
	 */	
	private function write($text, $unit, $level) {
		
		self::$lines[] = array("text" => $text, "unit" => $unit, "time" => date("H:i:s"), "level" => $level);
	} 


	/**
	 * 	@name message
	 * 	Function wirte log line with level "NOTICE"
	 * 	
	 * 	@param text : string
	 * 	@param unit : string (not required)
	 */
	public static function message($text, $unit = "") {
		
		self::write($text, $unit, "");
	}

	
	/**
	 * 	@name notice
	 * 	Function wirte log line without level (empty one)
	 * 	
	 * 	@param text : string
	 * 	@param unit : string (not required)
	 */
	public static function notice($text, $unit = "") {
		
		self::write($text, $unit, "NOTICE");
	}	
	
	
	/**
	 * 	@name error
	 * 	Function wirte log line with level "ERROR"
	 * 	
	 * 	@param text : string
	 * 	@param unit : string (not required)
	 */
	public static function error($text, $unit = "") {
		
		self::write($text, $unit, "ERROR");

		self::save();
	}
	
	
	/**
	 * 	@name error
	 * 	Function wirte log line with level "SUCCESS"
	 * 	
	 * 	@param text : string
	 * 	@param unit : string (not required)
	 */
	public static function success($text, $unit = "") {
		
		self::write($text, $unit, "SUCCESS");
	}
	

	/**
	 * 	@name save
	 * 	Function wirte log lines into output .txt file.
	 * 	
	 * 	@param path : string
	 */	
	public static function save() {

		$logs_path = APPLICATION_LOGS_PATH;

		$log_name = explode(" ", microtime());

		$log_name = $log_name[1] . ".txt";

		$file_path = $logs_path . $log_name;

		$lines_to_write = "";

		foreach(self::$lines as $line) {

			$lines_to_write .= "[" . $line["time"] . " | " . strval(empty($line["level"]) ? "\t" : $line["level"]) . "\t| " . $line["unit"] . "]\t\t" . $line["text"] . "\n";
		}

		file_put_contents($file_path, $lines_to_write);

		unset($lines_to_write);

		exit;
	}
}