<?php

class Logger {
    private $logFile;
    private $logLevel;

    // Supported log levels
    const LOG_LEVELS = [
        'DEBUG' => 1,
        'INFO' => 2,
        'WARNING' => 3,
        'ERROR' => 4,
    ];

    // Constructor to set the log file path and log level
    public function __construct($logFile = '/logs/app.log', $logLevel = 'DEBUG') {
        $this->logFile = dirname(__DIR__) . $logFile;
        $this->logLevel = self::LOG_LEVELS[$logLevel] ?? self::LOG_LEVELS['DEBUG'];

        // Create the log directory if it doesn't exist
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
    }

    // Method to log a message
    public function log($level, $message) {
        if (self::LOG_LEVELS[$level] >= $this->logLevel) {
            $date = date('Y-m-d H:i:s');
            $logMessage = "[{$date}] [{$level}] {$message}\n";
            file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        }
    }

    // Convenience methods for different log levels
    public function debug($message) {
        $this->log('DEBUG', $message);
    }

    public function info($message) {
        $this->log('INFO', $message);
    }

    public function warning($message) {
        $this->log('WARNING', $message);
    }

    public function error($message) {
        $this->log('ERROR', $message);
    }
}
