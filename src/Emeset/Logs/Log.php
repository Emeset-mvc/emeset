<?php
/**
 * Log Class for managing application logging.
 *
 * @author Eduard Martínez eduard.martinez.teixidor@gmail.com
 *
 * Provides a flexible logging system that supports multiple backends:
 * - Monolog library (if installed and configured)
 * - MySQL database logging
 * - File-based logging
 *
 * The logger automatically detects Monolog availability from composer.json and caches
 * the result in the .env file as LOG_USE_MONOLOG for performance optimization.
 *
 * @package Emeset\Logs
 * @version 1.0
 */

namespace Emeset\Logs;
class Log{
    /** @var mixed Database connection instance or null */
    private $db;
    /** @var bool Whether to use Monolog library for logging */
    private $monolog;
    /** @var bool|null Cached Monolog availability flag to avoid repeated checks */
    private static ?bool $cachedMonolog = null;
    /** @var string Directory path where log files will be stored */
    private string $logDir;

    /**
     * Constructor for Log class.
     *
     * Initializes the logger with optional database connection and Monolog flag.
     * Automatically detects Monolog availability if not explicitly provided.
     *
     * @param mixed $db Database connection (PDO or similar), or null for file-based logging
     * @param bool|null $monolog Optional override for Monolog usage. If null, auto-detects from composer.json
     */
    public function __construct($db, $monolog = null) {
        $this->db = $db;
        $this->monolog = $monolog === null ? $this->checkMonologFlag() : $monolog;
        $this->logDir = $this->detectProjectRoot() . DIRECTORY_SEPARATOR . 'Log';
    }

 /**
     * Main logging function that routes to appropriate logging backend.
     *
     * This method intelligently routes log calls to the appropriate backend based on
     * the configured logging system (Monolog, database, or file-based).
     *
     * Supported log levels:
     * - info, notice, warning, error, critical, alert, emergency
     *
     * For Monolog-specific levels, see vendor/monolog/monolog/src/Monolog/Level.php
     *
     * @param string $context The logging context/category (e.g., 'Authentication', 'Payment')
     * @param string $message The message to log
     * @param string $level The severity level of the log (default: 'info')
     * @param bool $cron Whether this log was triggered by a cron job (default: false)
     *
     * @return void
     */
    public function doLog($context = 'Context testing Function', $message = 'Message testing function', $level = 'info', $cron = false){
        $user = $this->getUser($cron);
        if ($this->db == null){
            if ($this->monolog){
                $this->logMonologFile($context, $message, $level, $user);
                return;
            }
            $this->logWithoutMonologFile($context, $message, $level, $user);
            return;
        }
        if ($this->monolog){
            $this->logMonologPDO($context, $message, $level, $user);
            return;
        }
        $this->logWithoutMonologPDO($context, $message, $level, $user);
    }

        /**
     * Retrieves the current user identifier for logging purposes.
     *
     * Determines the user from the active session or marks as 'cron' if triggered by a scheduled task.
     * Falls back to 'guest' if no session is found.
     *
     * @param bool $cron Whether the log is from a cron job
     * @return string The user identifier ('cron', 'admin', 'user', or 'guest')
     */
    private function getUser($cron): string {
        if ($cron) {
            return 'cron';
        }
        
        $user = 'guest';
        $userSession = \Emeset\Env::get("session_user" , null);
        $userRole = \Emeset\Env::get("session_userRole" , null);
        $userNick = \Emeset\Env::get("session_userNickname" , null);
        
        if ($userSession != null && $userRole != null && $userNick != null) {
            if (isset($_SESSION[$userSession][$userRole])) {
                $user = $_SESSION[$userSession][$userRole] === 'admin' ? $_SESSION[$userSession][$userNick] : 'user';
            }
        }
        
        return $user;
    }

/**
  * Logs to MySQL database using Monolog library.
  *
  * Configures a Monolog Logger with a custom MysqlHandler to persist logs to the database.
  *
  * @param string $context The logging context
  * @param string $message The log message
  * @param string $level The log severity level
  * @param string $user The user who triggered the log
  *
  * @return void
  */
    private function logMonologPDO($context, $message, $level, $user){
        $logger = new \Monolog\Logger($context);
        $logger->pushHandler(new \Emeset\Logs\MysqlHandler($this->db, $user));
        $logger->$level($message);
    }
/**
  * Logs to MySQL database without using Monolog library.
  *
  * Uses custom MysqlLogger class to persist logs directly to the database.
  *
  * @param string $context The logging context
  * @param string $message The log message
  * @param string $level The log severity level
  * @param string $user The user who triggered the log
  *
  * @return void
  */
    private function logWithoutMonologPDO($context, $message, $level, $user){
        $logger = new \Emeset\Logs\MysqlLogger($this->db, $user);
        $logger->log($context, $message, $level);
    }

    /**
     * Logs to file using Monolog library.
     *
     * Creates daily log files with formatted output including timestamp, level, and message.
     * Files are created in the Log directory with naming format: dd-mm-yyyy.log
     *
     * @param string $context The logging context
     * @param string $message The log message
     * @param string $level The log severity level
     * @param string $user The user who triggered the log
     *
     * @return void
     */
    private function logMonologFile($context, $message, $level, $user){
        $today = date('d-m-Y');
        $log = new \Monolog\Logger($context);
        $handler = new \Monolog\Handler\StreamHandler($this->logDir . DIRECTORY_SEPARATOR . $today . '.log');
        $formatter = new \Monolog\Formatter\LineFormatter("[%datetime%] %level_name%: %message%\n", "Y-m-d H:i:s");
        $handler->setFormatter($formatter);
        $log->pushHandler($handler);
        $log->$level($message, ['user' => $user]);
    }
    /**
     * Logs to file without using Monolog library.
     *
     * Uses custom FileLogger class for file-based logging.
     *
     * @param string $context The logging context
     * @param string $message The log message
     * @param string $level The log severity level
     * @param string $user The user who triggered the log
     *
     * @return void
     */
    private function logWithoutMonologFile($context, $message, $level, $user){
        $logger = new \Emeset\Logs\FileLogger($this->logDir, $user);
        $logger->log($context, $message, $level);
    }

    /**
     * Detects the project root directory by locating composer.json.
     *
     * Walks up the directory tree from the current file location until composer.json is found.
     * Intelligently skips vendor directories to find the actual project root rather than
     * the package's own composer.json.
     *
     * This ensures correct root detection even when the package is installed deep within vendor/.
     *
     * @return string The absolute path to the project root, or getcwd() as fallback
     */
    private function detectProjectRoot(): string {
        $path = __DIR__;
        
        // First, skip out of vendor directory if we're inside one
        while (basename($path) !== 'vendor' && $path !== dirname($path)) {
            $path = dirname($path);
        }
        // Move one level up from vendor to reach project root
        if (basename($path) === 'vendor') {
            $path = dirname($path);
        }
        
        // Now search for composer.json starting from project root
        for ($i = 0; $i < 15; $i++) {
            if (file_exists($path . DIRECTORY_SEPARATOR . 'composer.json')) {
                return $path;
            }
            $parent = dirname($path);
            if ($parent === $path) {
                break;
            }
            $path = $parent;
        }
        return getcwd();
    }

    /**
     * Determines Monolog library availability with caching and persistence.
     *
     * Resolution order:
     * 1. Checks static cache (per-process)
     * 2. Checks .env file for LOG_USE_MONOLOG setting
     * 3. Scans composer.json for monolog/monolog dependency
     * 4. Persists result to .env file for future checks
     *
     * This optimization prevents redundant file/directory scans after the first check.
     *
     * @return bool True if Monolog is available and should be used
     */
    private function checkMonologFlag(): bool {
        if (self::$cachedMonolog !== null) {
            return self::$cachedMonolog;
        }

        // Try to read from .env first
        $envValue = \Emeset\Env::get("LOG_USE_MONOLOG", null);
        if ($envValue !== null) {
            self::$cachedMonolog = ($envValue === 'true' || $envValue === true);
            return self::$cachedMonolog;
        }

        // Not in .env, check composer.json and persist
        $detected = $this->detectMonologInComposer();
        $this->persistEnvFlag($detected);
        self::$cachedMonolog = $detected;
        return self::$cachedMonolog;
    }

    /**
     * Scans composer.json for the monolog/monolog package.
     *
     * Checks both 'require' and 'require-dev' sections to determine if Monolog
     * is available as a project dependency.
     *
     * @return bool True if monolog/monolog is found in composer.json
     */
    private function detectMonologInComposer(): bool {
        $root = $this->detectProjectRoot();
        $composerPath = $root . DIRECTORY_SEPARATOR . 'composer.json';
        if (!file_exists($composerPath)) {
            return false;
        }
        $json = json_decode(file_get_contents($composerPath), true);
        if (!is_array($json)) {
            return false;
        }
        $deps = array_merge($json['require'] ?? [], $json['require-dev'] ?? []);
        return isset($deps['monolog/monolog']);
    }

    /**
     * Persists the Monolog availability flag to the .env file.
     *
     * Writes or updates the LOG_USE_MONOLOG setting in the .env file. Only writes
     * if the value has changed to minimize file I/O. Creates the .env file if it
     * doesn't exist.
     *
     * @param bool $value True to enable Monolog, false to disable
     *
     * @return void
     */
    private function persistEnvFlag(bool $value): void {
        $root = $this->detectProjectRoot();
        $envPath = $root . DIRECTORY_SEPARATOR . '.env';
        $lines = file_exists($envPath)
            ? file($envPath, FILE_IGNORE_NEW_LINES)
            : [];
        
        // Check if the correct value already exists
        $expectedLine = 'LOG_USE_MONOLOG = ' . ($value ? 'true' : 'false');
        foreach ($lines as $line) {
            if (trim($line) === $expectedLine) {
                return; // Already correct, no need to write
            }
        }
        
        // Remove old LOG_USE_MONOLOG lines and add new one
        $filtered = array_values(array_filter($lines, function ($line) {
            return strpos($line, 'LOG_USE_MONOLOG=') !== 0 && strpos($line, 'LOG_USE_MONOLOG =') !== 0;
        }));
        $filtered[] = $expectedLine;
        file_put_contents($envPath, implode(PHP_EOL, $filtered) . PHP_EOL);
    }
}