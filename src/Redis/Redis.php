<?php

/*
 * This file is part of the shawsola/easyredis.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Easy\Redis;

use Easy\Exception\ConnectException;
use Easy\Exception\ResponseException;

class Redis
{
    private $instance;

    private $host;

    private $port;

    private $timeout;

    public function __construct($host = '127.0.0.1', $port = 6379, $timeout = 30)
    {
        $this->host = $host;

        $this->port = $port;

        $this->timeout = $timeout;
    }

    public function close()
    {
        if (!empty($this->instance)) {
            fclose($this->instance);
        }
        $this->instance = null;
    }

    private function getInstance()
    {
        if (!empty($this->instance)) {
            return $this->instance;
        }
        $this->instance = pfsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
        if (!$this->instance) {
            throw new ConnectException($errno.' - '.$errstr);
        }

        return $this->instance;
    }

    public function __call($method, array $argument)
    {
        array_unshift($argument, $method);
        $cmd = '*'.count($argument)."\r\n";
        foreach ($argument as $value) {
            $cmd .= '$'.mb_strlen($value)."\r\n{$value}\r\n";
        }
        fwrite(self::getInstance(), $cmd);

        return $this->response();
    }

    private function response()
    {
        $line = fgets(self::getInstance());
        list($type, $result) = [$line[0], substr($line, 1, strlen($line) - 3)];
        if ('-' === $type) {
            throw new ResponseException($result);
        } elseif ('$' === $type) {
            if ($result === -1) {
                $result = null;
            } else {
                $line = fread(self::getInstance(), $result + 2);
                $result = substr($line, 0, strlen($line) - 2);
            }
        } elseif ('*' === $type) {
            $count = (int) $result;
            for ($i = 0, $result = []; $i < $count; ++$i) {
                $result[] = $this->response();
            }
        }

        return $result;
    }
}
