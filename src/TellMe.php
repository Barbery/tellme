<?php

namespace Barbery\TellMe;

use Exception;
use Throwable;

class TellMe
{
    private $config = [];
    private $data   = [];

    const DEBUG_LIMIT = 50;

    public function __construct($config = [])
    {
        $coreConfig   = include 'config/config.php';
        $this->config = array_merge($coreConfig, $config);
    }

    public static function registerThrowableHandler($config)
    {
        $exceptionHandler = function ($e) use ($config) {
            $class = self::class;
            (new $class($config))->send($e);
            throw $e;
        };

        $errorHandler = function ($errno, $errstr, $errfile, $errline, $errcontext) {
            $class = self::class;
            (new $class($config))->setData([
                'code'    => $errno,
                'message' => $errstr,
                'file'    => $errfile,
                'line'    => $errline,
            ])->send();

            return false;
        };

        set_exception_handler($exceptionHandler);
        set_error_handler($errorHandler);
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function send(Throwable $e = null)
    {
        $this->_initErrorVars($e);
        foreach ($this->config['channels'] as $channel) {
            $Provider = $this->getProvider($channel);
            $Provider->translate($this->data)->send();
        }
    }

    public function getProvider($channel)
    {
        if (empty($channel['provider']) || empty($this->config['providers'][$channel['provider']])) {
            throw new Exception('Provider not found');
        }

        $provider = $this->config['providers'][$channel['provider']];

        return new $provider($channel);
    }

    private function _initErrorVars(Throwable $e)
    {
        $errorVars = [];
        if (!empty($e)) {
            $errorVars = [
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'trace'   => $this->getTrace($e),
                'level'   => $e instanceof Execption ? 'Execption' : $this->config['error_level_map'][$e->getCode()],
            ];
        }

        $this->data['time'] = date('Y-m-d H:i:s');
        $this->data         = array_merge($errorVars, $this->data);
    }

    private function getTrace(Throwable $e = null)
    {
        return function () use ($e) {
            static $trace = null;
            if (empty($trace)) {
                if (is_object($e)) {
                    $trace = $e->getTraceAsString();
                } else {
                    ob_start();
                    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, self::DEBUG_LIMIT);
                    $trace = ob_get_flush();
                }
            }

            return $trace;
        };
    }
}
