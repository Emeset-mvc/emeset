<?php

namespace App\Logs;
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
    private $cron;

    public function __construct(PDO $pdo, $cron, int|string|Level $level = Level::Debug, bool $bubble = true)
    {
        $this->pdo = $pdo;
        parent::__construct($level, $bubble);
        $this->cron = $cron;
    }

    protected function write(LogRecord $record): void
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
            'channel' => $record->channel,
            'level' => $record->level->value,
            'message' => $record->message,
            'user' => $user,
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