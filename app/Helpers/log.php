<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

if (!function_exists('logger_save')) {
    /**
     * @param $message
     * @param null $data
     * @param string $filename
     * @param bool $isDate
     * @param string $type
     */
    function logger_save($message, $data = null, $filename = '', $isDate = true, $type = '')
    {
        $log = new Logger('mylog');
        if (PHP_SAPI == 'cli') {
            $filename .= '_cli';
        }

        $filename = $filename . '.log';

        if ($isDate) {
            $path = storage_path('logs/' . date('Y-m-d'));
        } else {
            $path = storage_path('logs/');
        }

        mkDirs($path);

        $path = $path . '/' . $filename;
        if (gettype($data) != 'array') {
            $message .= "：" . $data;
            $data = array();
        }

        $log->pushHandler(new StreamHandler($path, Logger::INFO));
        switch ($type) {
            case 'info':
                $log->info($message, $data);
                break;
            case 'error':
                $log->error($message, $data);
                break;
            case 'warning':
                $log->warning($message, $data);
                break;
            case 'debug':
                $log->debug($message, $data);
                break;
        }
    }
}

if (!function_exists('mkDirs')) {
    function mkDirs($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) {
            return TRUE;
        }
        if (!mkdirs(dirname($dir), $mode)) {
            return FALSE;
        }
        return @mkdir($dir, $mode);
    }
}

if (!function_exists('logDebug')) {
    function logDebug($message, $data = null, $filename = 'info', $isDate = true, $isType = 'debug')
    {
        logger_save($message, $data, $filename, $isDate, $isType);
    }
}
if (!function_exists('logInfo')) {
    function logInfo($message, $data = null, $filename = 'info', $isDate = true, $isType = 'info')
    {
        logger_save($message, $data, $filename, $isDate, $isType);
    }
}
if (!function_exists('logWarning')) {
    function logWarning($message, $data = null, $filename = 'warning', $isDate = true, $isType = 'warning')
    {
        logger_save($message, $data, $filename, $isDate, $isType);
    }
}
if (!function_exists('log_error')) {
    function logError($message, $data = null, $filename = 'error', $isDate = true, $isType = 'error')
    {
        logger_save($message, $data, $filename, $isDate, $isType);
    }
}