<?php
/**
 * MysqlLogger Class for direct database logging without Monolog.
 *
 * @author Eduard Martínez eduard.martinez.teixidor@gmail.com
 *
 * Manages writing logs directly to MySQL database without external dependencies.
 **/

namespace Emeset\Logs;
use PDO;
use PDOStatement;


class MysqlLogger
{
    private bool $initialized = false;
    private PDO $pdo;
    private PDOStatement $statement;
    private $user;

    public function __construct(PDO $pdo, $user)
    {
        $this->pdo = $pdo;
        $this->user = $user;
    }
    /**
     * Does the log writing in the database
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
        $this->statement->execute(array(
            'channel' => $context,
            'level' => $level,
            'message' => $message,
            'user' => $this->user,
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