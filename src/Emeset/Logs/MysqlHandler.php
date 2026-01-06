<?php
/**
 * MysqlHandler Class for Monolog-based database logging.
 *
 * @author Eduard Martínez eduard.martinez.teixidor@gmail.com
 *
 * Handler for Monolog that persists log records to MySQL database.
 **/

namespace Emeset\Logs;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Handler\AbstractProcessingHandler;
use PDO;
use PDOStatement;


class MysqlHandler extends AbstractProcessingHandler
{
    private bool $initialized = false;
    private PDO $pdo;
    private PDOStatement $statement;
    private $user;

    public function __construct(PDO $pdo, $user, int|string|Level $level = Level::Debug, bool $bubble = true)
    {
        $this->pdo = $pdo;
        parent::__construct($level, $bubble);
        $this->user = $user;
    }
    /**
     * User is passed from Log.php
     * 
     * Record has the following properties: channel, level, message, context, datetime, extra.
     * 
     * We only use channel, level and message here.
     * 
     * @param LogRecord $record
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        if (!$this->initialized) {
            $this->initialize();
        }
        $this->statement->execute(array(
            'channel' => $record->channel,
            'level' => $record->level->value,
            'message' => $record->message,
            'user' => $this->user,
        ));
    }

    private function initialize()
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS logs '
            .'(id INT NOT NULL auto_increment primary key, channel VARCHAR(255), user varchar(250), level INTEGER, message LONGTEXT, time DATETIME DEFAULT CURRENT_TIMESTAMP())'
        );
        $this->statement = $this->pdo->prepare(
            "INSERT INTO logs (channel, user, level, message, time) VALUES (:channel, :user, :level, :message, CURRENT_TIMESTAMP())"
        );

        $this->initialized = true;
    }
}