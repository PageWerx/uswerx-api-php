<?php

namespace Pagewerx\UswerxApiPhp\Contracts;
interface LoggerInterface
{
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;
    const LEVELS = [
        self::DEBUG => 'DEBUG',
        self::INFO => 'INFO',
        self::NOTICE => 'NOTICE',
        self::WARNING => 'WARNING',
        self::ERROR => 'ERROR',
        self::CRITICAL => 'CRITICAL',
        self::ALERT => 'ALERT',
        self::EMERGENCY => 'EMERGENCY',
    ];

    /**
     * Logs a message with a specific level and additional context.
     * For convenience the levels are defined as constants in this interface.
     * Possible values are 'DEBUG', 'INFO', 'WARNING', 'ERROR', 'CRITICAL', 'ALERT', and 'EMERGENCY'.
     * The functions named after the levels are shortcuts for logging at the respective level.
     * Of course, you can implement the methods as you see fit.
     *
     * @param string $message The message to be logged.
     * @param int $level The log level for the message. Possible values are 'DEBUG', 'INFO', 'WARNING', 'ERROR', 'CRITICAL', and 'ALERT'.
     * @param array $context Additional context to be logged along with the message.
     *
     * @return void
     */
    function log(string $message, int $level, array $context): void;

    function debug(string $message, array $context): void;

    function info(string $message, array $context): void;

    function notice(string $message, array $context): void;

    function warning(string $message, array $context): void;

    function error(string $message, array $context): void;

    function critical(string $message, array $context): void;

    function alert(string $message, array $context): void;

    function emergency(string $message, array $context): void;
}