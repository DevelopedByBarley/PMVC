<?php

namespace Core;

class Log
{

  public static function log($level, $logFile, $message, $dev)
  {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$level}: {$message}" . PHP_EOL;

    if ($dev !== null) {
      $logMessage .= "[DEV] {$dev}" . PHP_EOL;
    }

    file_put_contents(base_path("storage/logs/$logFile.log"), $logMessage, FILE_APPEND);
  }


  public static function info($message, $dev = null, $logFile = 'app')
  {
    self::log('INFO', $logFile, $message, $dev);
  }

  public static function error($message, $dev = null, $logFile = 'app')
  {
    self::log('ERROR', $logFile, $message, $dev);
  }

  public static function warning($message, $dev = null, $logFile = 'app')
  {
    self::log('WARNING', $logFile, $message, $dev);
  }

  public static function critical($message, $dev = null, $logFile = 'app')
  {
    self::log('CRITICAL', $logFile, $message, $dev);
  }
}
