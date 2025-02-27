<?php declare(strict_types=1);

namespace Boctulus\ApiClient\Helpers;

use Boctulus\ApiClient\Helpers\Url; 
use Boctulus\ApiClient\Helpers\Logger;

// Dumper
class VarDump
{
	public static $render       = true;
	public static $render_trace = false;
	protected static $log       = false;

	/*
		Falla silenciosamente en segundo plano
	*/
	static function log(bool $value = true){
		static::$log = $value;
	}

	// Corregir porque a veces.... no es con count($trace)-1 por alguna razon
	static public function traceMe(){
		$trace = debug_backtrace();
		
		$file  = $trace[count($trace)-1]['file'];
		$line  = $trace[count($trace)-1]['line'];

		static::export("{$file}:{$line}", "LOCATION", true);
	}

	static function p(){
		return (php_sapi_name() == 'cli' || Url::isPostmanOrInsomnia()) ? PHP_EOL . PHP_EOL : '<p/>';
	}
	static function br(){
		return (php_sapi_name() == 'cli' || Url::isPostmanOrInsomnia())  ? PHP_EOL : '<br/>';;
	}

	protected static function pre(callable $fn, ...$args){
		echo '<pre>';
		$fn($args);
		echo '</pre>';
	}

	protected static function export($v = null, $msg = null, bool $additional_carriage_return = false, bool $msg_at_top = true) 
	{	
		$type = is_null($v) ? 'null' : gettype($v);

		$postman = Url::isPostmanOrInsomnia();
		
		$cli     = (php_sapi_name() == 'cli');
		$br      = static::br();
		$p       = static::p();

		$pre = !$cli;	

		if ($postman || $type != 'array'){
			$pre = false;
		}
		
		$fn = function($x) use ($type, $postman, $pre){
			$pp = function ($fn, $dato) use ($pre){
				if ($pre){
					self::pre(function() use ($fn, $dato){ 
						$fn($dato);
					});
				} else {
					$fn($dato);
				}
			};

			switch ($type){
				case 'NULL':
					$pp('print_r', '<null>' . static::br());
				break;
				case 'boolean':
				case 'string':
				case 'double':
				case 'float':
					$pp('print_r', $x);
					break;
				case 'array':
					if ($postman){
						$pp('var_export', $x);
					} else {
						$pp('print_r', $x);
					}
					break;	
				case 'integer':
					$pp('var_export', $x);
					break;
				default:
				$pp('var_dump', $x);
			}	
		};
		
		if ($type == 'boolean'){
			$v = $v ? 'true' : 'false';
		}	

		if ($msg_at_top && !empty($msg)){
			$cfg = config();
			$ini = $cfg['var_dump_separators']['start'] ?? '--| ';
			$end = $cfg['var_dump_separators']['end']   ?? '';

			echo "{$ini}$msg{$end}". (!$pre ? $br : '');
		}
			
		$fn($v);			
	
		switch ($type){
			case 'boolean':
			case 'string':
			case 'double':
			case 'float':	
			case 'integer':
				$include_break = true;
				break;
			case 'array':
				$include_break = $postman;
				break;	
			default:
				$include_break = false;
		}	

		if (!$msg_at_top && !empty($msg)){
			$cfg = config();
			$ini = $cfg['var_dump_separators']['start'] ?? '--| ';
			$end = $cfg['var_dump_separators']['end']   ?? '';

			echo "{$ini}$msg{$end}". (!$pre ? $br : '');
		}

		if (!$cli && !$postman && $type != 'array'){
			echo $br;
		}

		if ($include_break && ($cli ||$postman)){
			echo $br;
		}

		if ($additional_carriage_return){
			echo $br;
		}
	}	

	static public function dd($val = null, $msg = null, bool $additional_carriage_return = false, bool $msg_at_top = true)
	{
		if (!static::$render){
			return;
		}

		if (static::$render_trace){
			$trace = debug_backtrace();
			$file  = debug_backtrace()[count($trace)-1]['file'] ?? '?';
			$line  = debug_backtrace()[count($trace)-1]['line'] ?? '?';

			static::export("{$file}:{$line}", "LOCATION", true, $msg_at_top);
		}

		if (static::$log){
			Logger::dd($val, $msg);
		}		

		self::export($val, $msg, $additional_carriage_return, $msg_at_top);
	}

	static function hideResponse(){
        self::$render = false;
    }

    static function showResponse(bool $status = true){
        self::$render = $status;
    }

	static function hideTrace(){
        self::$render_trace = false;
    }

    static function showTrace(bool $status = true){
        self::$render_trace = $status;
    }
}