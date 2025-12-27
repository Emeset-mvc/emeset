<?php

namespace App\Logs;
use PDO;
use PDOStatement;


class MysqlLogger
{
    private bool $initialized = false;
    private PDO $pdo;
    private PDOStatement $statement;
    private $cron;

    public function __construct(PDO $pdo, $cron)
    {
        $this->pdo = $pdo;
        $this->cron = $cron;
    }
    /**
     * Does the log writing in the database
     * 
     * If cron is true, the user will be 'cron', else it will check the session for user info. 
     * 
     * Session values must be set in .env file with keys: session_user, session_userRole, session_userNickname
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
        if ($this->cron) {
            $user = 'cron';
        } else {
            $userSession = \Emeset\Env::get("session_user" , null);
            $userRole = \Emeset\Env::get("session_userRole" , null);
            $userNick = \Emeset\Env::get("session_userNickname" , null);
            if ($userSession != null && $userRole != null && $userNick != null) {
                if (isset($_SESSION[$userSession][$userRole])) {
                    $user = $_SESSION[$userSession][$userRole] === 'admin' ? $_SESSION[$userSession][$userNick] : 'user';
                }
            }
        }
        $this->statement->execute(array(
            'channel' => $context,
            'level' => $level,
            'message' => $message,
            'user' => $user,
        ));
    }
    /**
     * Sets up table if not created and prepares query
     * @return void
     */
    private function initialize()
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS logs (id INT NOT NULL auto_increment primary key, channel VARCHAR(255), user varchar(250), level INTEGER, message LONGTEXT, time DATETIME DEFAULT CURRENT_TIMESTAMP())'
        );
        $this->statement = $this->pdo->prepare(
            "INSERT INTO logs (channel, user, level, message, time) VALUES (:channel, :user, :level, :message, CURRENT_TIMESTAMP())"
        );

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