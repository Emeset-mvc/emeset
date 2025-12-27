<?php
namespace App\Logs;
use Monolog\Logger;

class Log{
    private $db;
    private $monolog;

    public function __construct($db, $monolog = false) {
        $this->db = $db;
        $this->monolog = $monolog;
    }

 /**
  * doLog function for creating logs in database
  *
  * @param string $context
  * @param string $message
  * @param string $level
  *
  * Use one of the following levels: ['info' | 'notice' | 'warning' | 'error' | 'critical' | 'alert' | 'emergency']
  * 
  * If using Monolog, check ```vendor/monolog/monolog/src/Monolog/Level.php``` for more info about levels
  * @param bool $cron
  * @return void
  */
    public function doLog($context = 'Context testing Function', $message = 'Message testing function', $level = 'info', $cron = false){
        if ($this->monolog){
            $this->logMonolog($context, $message, $level, $cron);
            return;
        }
        $this->logWithoutMonolog($context, $message, $level, $cron);
    }

/**
  * logMonolog => Works if using monolog library
  *
  * Does log in MySQL database using Monolog
  * @return void
  */
    private function logMonolog($context, $message, $level, $cron){
        $logger = new Logger($context);
        $logger->pushHandler(new \App\Logs\MysqlHandler($this->db, $cron));
        $logger->$level($message);
    }
/**
  * logWithoutMonolog => Works if not using monolog library
  *
  * Does log in MySQL database without using Monolog
  * @return void
  */
    private function logWithoutMonolog($context, $message, $level, $cron){
        $logger = new \App\Logs\MysqlLogger($this->db, $cron);
        $logger->log($context, $message, $level);
    }
}