<?php
declare(strict_types=1) ;
/*************************************************************************
    Copyright 2020  HALCYON CYBERNETICS

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
**************************************************************************/

// const LOGGING  = true ;
// const LOG_PATH = '/tmp/' ;
// const DEBUG_LOG_FILE = 'php_degug.log' ;
// const INFO_LOG_FILE  = 'php_info.log'  ;
// const TRACE_LOG_FILE = 'php_trace.log' ;
// const ERROR_LOG_FILE = 'php_error.log' ;

defined('LOGGING')  or define('LOGGING', false);
defined('LOG_PATH') or define('LOG_PATH', '/tmp/');
defined('DEBUG_LOG_FILE') or define('DEBUG_LOG_FILE', 'php_degug.log') ;
defined('INFO_LOG_FILE')  or define('INFO_LOG_FILE',  'php_info.log')  ;
defined('TRACE_LOG_FILE') or define('TRACE_LOG_FILE', 'php_trace.log') ;
defined('ERROR_LOG_FILE') or define('ERROR_LOG_FILE', 'php_error.log') ;

ini_set("log_errors","on");

function D($param) {
    $caller  = debug_backtrace()[1] ;
    ini_set("error_log",LOG_PATH.DEBUG_LOG_FILE);
    Logger::getInstance()->log('[DEBUG]',$caller,$param) ;
}

function I($param) {
    $caller  = debug_backtrace()[1] ;
    ini_set("error_log",LOG_PATH.INFO_LOG_FILE);
    Logger::getInstance()->log('[INFO]',$caller,$param) ;
}

function T($param) {
    $caller  = debug_backtrace()[1] ;
    ini_set("error_log",LOG_PATH.TRACE_LOG_FILE);
    Logger::getInstance()->log('[TRACE]',$caller,$param) ;
}

function E($param) {
    $caller  = debug_backtrace()[1] ;
    ini_set("error_log",LOG_PATH.ERROR_LOG_FILE);
    Logger::getInstance()->log('[ERROR]',$caller,$param) ;
}

class Logger {
    private static $instance = null;

    private final function __construct(){}

    public static function getInstance() {
        return self::$instance ?? self::$instance = new Logger();
    }

    public function log(string $level ,array $caller ,mixed $param) {
        if(!LOGGING) return ;
        error_log($level.' '.
            ((isset($caller['class']))?$caller['class']:'').
            '::'.$caller['function'].':'.
            ((isset($caller['line']))?$caller['line']:'').' '.
            self::getParameterAsString($param)) ;
    }

    private function getParameterAsString($param): string {
        $message = '' ;
        $type    = gettype($param) ;
        switch($type) {
            case "boolean" :
                $message = ($param)?'true':'false' ;
                break    ;
            case "integer" :
            case "double"  :
                $message = (string)$param ;
                break    ;
            case "string"  :
                $message = $param ;
                break ;
            case "array" :
                $message = print_r($param,true) ;
                break ;
            case "object" :
                $message = print_r($param,true) ;
                break ;
            case "resource"          :
            case "resource (closed)" :
            case "NULL"              :
            case "unknown type"      :
            default                  :
                $message = $type ;
                break ;
        }
        return $message ;
    }
}