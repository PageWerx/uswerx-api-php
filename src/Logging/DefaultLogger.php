<?php
namespace Pagewerx\UswerxApiPhp\Logging;
use Exception;
use Pagewerx\UswerxApiPhp\Contracts\LoggerInterface;

class DefaultLogger implements LoggerInterface
{
    protected $path;
    protected $filename;
    public function __construct($path = null, $filename = null)
    {
        if ($path !== null) {
            $this->setLogPath($path);
        } else {
            $this->setLogPath(__DIR__."/../../logs/");
        }
        if ($filename !== null) {
            $this->setFilename($filename);
        } else {
            $this->setFilename(date('m-d-Y').".log");
        }
        // Create the log directory if it doesn't exist
        $this->createLogDirectory();
    }

    /**
     * Method to set the path for the log file.
     *
     * @param mixed $path
     * @return void
     */
    private function setLogPath(mixed $path)
    {
        $this->path = $path ?? __DIR__."/../../logs/";
    }

    /**
     * Method to set the filename for the log file.
     *
     * @param mixed $filename
     * @return void
     */
    private function setFilename(mixed $filename)
    {
        $this->filename = $filename ?? date('m-d-Y').".log";
    }

    private function createLogDirectory()
    {
        if (!file_exists($this->getLogPath())) {
            mkdir($this->getLogPath(), 0777, true);
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param string $message
     * @param int $level
     * @param array $context
     * @throws Exception
     */
    function log(string $message, int $level, array $context): void
    {
        try {
            $logHandle = $this->getLogPath().$this->getFilename();
            $logMessage = date('Y-m-d H:i:s')." [{$level}] {$message} ".json_encode($context).PHP_EOL;
            file_put_contents($logHandle, $logMessage, FILE_APPEND);
        } catch (Exception $e) {
            throw new Exception("Error writing to log file: ".$e->getMessage(), 500);
        }
    }

    function logForLevel(string $message, int $level, array $context): void
    {
        switch ($level) {
            case self::DEBUG:
            case self::INFO:
            case self::NOTICE:
                $this->setFilename("info-".date('m-d-Y').".log");
                $this->log($message, $level, $context);
                $this->setFilename($this->defaultLogFilename()); // Revert back to the default log file
                break;
            case self::WARNING:
                $this->warning($message, $context);
                break;
            case self::ERROR:
                $this->error($message, $context);
                break;
            case self::CRITICAL:
                $this->critical($message, $context);
                break;
            case self::ALERT:
                $this->alert($message, $context);
                break;
            case self::EMERGENCY:
                $this->emergency($message, $context);
                break;
            default:
                $this->log($message, $level, $context);
        }

        $this->log($message, $level, $context);
    }

    /**
     * Logs a DEBUG message with a specific level and additional context.
     * @throws Exception
     */
    function debug(string $message, array $context): void
    {
        $this->setFilename("info-".date('m-d-Y').".log");
        $this->log($message, self::DEBUG, $context);
        $this->setFilename($this->defaultLogFilename()); // Revert back to the default log file
    }

    /**
     * Logs an Informational message.
     *
     * @param string $message The message to be logged.
     * @param array $context The log context data.
     * @return void
     * @throws Exception If an error occurs while logging.
     */
    function info(string $message, array $context): void
    {
        $this->setFilename("info-".date('m-d-Y').".log");
        $this->log($message, self::INFO, $context);
        $this->setFilename($this->defaultLogFilename()); // Revert back to the default log file
    }

    /**
     * Logs a Notice message.
     *
     * @param string $message The message to be logged.
     * @param array $context The log context data.
     * @return void
     * @throws Exception If an error occurs while logging.
     */
    function notice(string $message, array $context): void
    {
        $this->setFilename("info-".date('m-d-Y').".log");
        $this->log($message, self::NOTICE, $context);
    }

    /**
     * Method to log a warning message.
     *
     * @param string $message The warning message to log.
     * @param array $context Additional context data to be included in the log message.
     * @return void
     * @throws Exception
     */
    function warning(string $message, array $context): void
    {
        $this->log($message, self::WARNING, $context);
    }

    /**
     * Method to log an error message.
     *
     * @param string $message The error message to log.
     * @param array $context Additional context data to be included in the log message.
     * @return void
     * @throws Exception
     */
    function error(string $message, array $context): void
    {
        $this->log($message, self::ERROR, $context);
    }

    /**
     * Method to log a critical message.
     *
     * @param string $message The critical message to log.
     * @param array $context Additional context data to be included in the log message.
     * @return void
     * @throws Exception
     */
    function critical(string $message, array $context): void
    {
        $this->log($message, self::CRITICAL, $context);
    }

    /**
     * Method to log an alert message.
     *
     * @param string $message The alert message to log.
     * @param array $context Additional context data to be included in the log message.
     * @return void
     * @throws Exception
     */
    function alert(string $message, array $context): void
    {
        $this->log($message, self::ALERT, $context);
    }

    /**
     * Method to log an emergency message.
     *
     * @param string $message The emergency message to log.
     * @param array $context Additional context data to be included in the log message.
     * @return void
     * @throws Exception
     */
    function emergency(string $message, array $context): void
    {
        $this->log($message, self::EMERGENCY, $context);
    }

    /**
     * Method to get the filename for logging purposes.
     *
     * @return string The filename to log to. If a filename is set, it will be returned, otherwise today's date followed by .log will be used.
     */
    function getFilename(): string
    {
        return $this->filename ?? date('m-d-Y').".log";
    }

    function defaultLogFilename(): string
    {
        return date('m-d-Y').".log";
    }

    /**
     * Method to retrieve the path of the log file.
     *
     * @return string The path of the log file.
     */
    function getLogPath(): string
    {
        return $this->path ?? __DIR__."/../../logs/";
    }

}