<?php
/**
 * Test Script for Emeset Logging Service.
 *
 * @author Eduard Martínez eduard.martinez.teixidor@gmail.com
 *
 * WARNING: This file must be placed in the public directory where index.php is located,
 * as it requires access to the Container and application configuration.
 *
 * This script demonstrates how to use the logging service of the Emeset framework.
 * The Log service allows recording messages to the database or files, both with and without the Monolog library.
 *
 * Functionality:
 * - If Log is initialized with the $monolog parameter as true, it uses the Monolog library to record logs.
 * - If $monolog is false (or not provided), it uses a custom implementation without Monolog.
 * - The database connection must be passed as the first parameter. If null, logs are written to files instead.
 * - Log files are stored in the /Log directory at the project root with the format: DD-MM-YYYY.log
 * - Each log entry includes a timestamp in DD-MM-YYYY HH:i:s format.
 *
 * Log Levels:
 * Available log levels: 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'
 * Refer to vendor/monolog/monolog/src/Monolog/Level.php for more details on Monolog levels.
 *
 * Usage Examples:
 *   // With database and Monolog
 *   $log = new \Emeset\Logs\Log($db, true);
 *   $log->doLog('Context', 'Message with Monolog', 'info');
 *
 *   // Without Monolog
 *   $log = new \Emeset\Logs\Log($db, false);
 *   $log->doLog('Context', 'Message without Monolog', 'warning');
 *
 *   // With null database (logs to file) using Monolog
 *   $log = new \Emeset\Logs\Log(null, true);
 *   $log->doLog('Context', 'File-based log with Monolog', 'error');
 *
 *   // With null database (logs to file) without Monolog
 *   $log = new \Emeset\Logs\Log(null, false);
 *   $log->doLog('Context', 'File-based log without Monolog', 'warning');
 *
 * Output:
 * - Logs with database connection: Stored in the database
 * - Logs without database connection: Stored in /Log/DD-MM-YYYY.log files
 * - All logs include date, context, message, level, and user information
 **/

require __DIR__ . '/../vendor/autoload.php';

// UNCOMMENT THIS if you don't have a database connection.
// $db = null;

// COMMENT THIS if you don't have a database connection or if it's not at the specified path.
// If you have a diferent path to get to config.php, please change it accordingly.
$db = (new \App\Container(__DIR__ . "/../App/config.php"))->get('Db')->getDb();

// Test 1: Log with Monolog and database
$logMonolog = new \Emeset\Logs\Log($db, true);
$logMonolog->doLog('TestMonolog', 'Test message with Monolog', 'info');

// Test 2: Log without Monolog and with database
$logNoMonolog = new \Emeset\Logs\Log($db, false);
$logNoMonolog->doLog('TestNoMonolog', 'Test message without Monolog', 'warning');

// Test 3: Log with Monolog without database (file-based)
$logMonologFile = new \Emeset\Logs\Log(null, true);
$logMonologFile->doLog('TestMonologFile', 'File-based test message with Monolog', 'warning');

// Test 4: Log without Monolog without database (file-based)
$logNoMonologFile = new \Emeset\Logs\Log(null, false);
$logNoMonologFile->doLog('TestNoMonologFile', 'File-based test message without Monolog', 'warning');

print_r ("All log tests completed successfully. Check the database or /Log directory for results.\n");