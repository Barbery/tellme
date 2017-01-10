<?php

namespace Barbery\TellMe;

use Exception;

class TellMe
{
    private $config = [];

    const DEBUG_LIMIT = 100;

    public function __construct($config = [])
    {
        $coreConfig   = include 'config/config.php';
        $this->config = array_merge($coreConfig, $config);
    }

    public function send($data = [])
    {
        if ($data instanceof Exception) {
            $data = $this->_initExceptionVars($data);
        } else {
            $data = array_merge($this->_initVars(), $data);
        }

        foreach ($this->config['channels'] as $channel) {
            $Provider = $this->getProvider($channel);
            $Provider->translate($data)->send();
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

    private function _initErrorVars()
    {
        $errors = error_get_last();
        if (empty($errors)) {
            return [];
        }

        $errors['level'] = $this->config['error_level_map'][$errors['type']];
        $errors['code']  = 500;
        ob_start();
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, self::DEBUG_LIMIT);
        $errors['trace'] = ob_get_flush();

        return $errors;
    }

    private function _initExceptionVars(Exception $e)
    {
        return [
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
            'trace'   => $e->getTraceAsString(),
            'level'   => 'Execption',
        ];
    }
}
