<?php
/**
 * FileLogger Class for file-based logging.
 *
 * @author Eduard Martínez eduard.martinez.teixidor@gmail.com
 *
 * Manages the writing of logs to text files organized by day.
 **/

namespace Emeset\Logs;


class FileLogger
{
    private bool $initialized = false;
    private $user;
    private string $logDir;
    private string $logFile;

    public function __construct($logDir, $user)
    {
        $this->logDir = $logDir;
        $this->user = $user;
    }
    /**
     * Does the log writing to file
     * 
     * User is passed from Log.php
     * 
     * @param mixed $context
     * @param mixed $message
     * @param mixed $level
     * @return void
     */
    private function write($context, $message, $level): void
    {
        if (!$this->initialized) {
            $this->initialize();
        }
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = $timestamp . " | - | " . $context . " | - | " . $this->user . " | - | " . $level . " | - | " . $message . "\n";
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
    /**
     * Sets up Log folder and creates daily log file if not exists
     * 
     * @return void
     */
    private function initialize()
    {        
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }
        
        $today = date('d-m-Y');
        $this->logFile = $this->logDir . DIRECTORY_SEPARATOR . $today . '.log';

        if (!file_exists($this->logFile)) {
            touch($this->logFile);
        }

        $this->initialized = true;
    }
    /**
     * Executes write log function.
     * 
     * Validates level input to one of the accepted levels.
     *  
     * @param mixed $context
     * @param mixed $message
     * @param mixed $level
     * @return void
     */
    public function log($context, $message, $level){
        if ($level != 'info' && $level != 'notice' && $level != 'warning' && $level != 'error' && $level != 'critical' && $level != 'alert' && $level != 'emergency'){
            $level = 'info';
        }
        $this->write($context, $message, $level);
    }
}