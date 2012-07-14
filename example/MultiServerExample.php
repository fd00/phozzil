<?php

require_once '../src/phozzil/Phozzil.php';

use phozzil\net\MultiServer;
use phozzil\net\MultiServerAdapter;

abstract class MultiServerAdapterExample extends MultiServerAdapter
{
    public function __construct($port)
    {
        parent::__construct($port);
    }

    public function onConnect($resource)
    {
        $socket = stream_socket_accept($resource);
        if ($socket === false) {
            return;
        }
        $this->addSocket($socket);
    }

    public function onData($resource)
    {
        $string = stream_socket_recvfrom($resource, 8192);
        stream_socket_sendto($resource, $this->convertData($string));
        stream_socket_shutdown($resource, STREAM_SHUT_RDWR);
        $this->removeSocket($resource);
    }

    abstract function convertData($data);
}

class MultiServerAdapterExample1 extends MultiServerAdapterExample
{
    public function __construct($port)
    {
        parent::__construct($port);
    }

    public function convertData($data)
    {
        return strrev($data);
    }
}

class MultiServerAdapterExample2 extends MultiServerAdapterExample
{
    public function __construct($port)
    {
        parent::__construct($port);
    }

    public function convertData($data)
    {
        return str_rot13($data);
    }
}

$server = new MultiServer();
$server->addListener(new MultiServerAdapterExample1(7743));
$server->addListener(new MultiServerAdapterExample2(7744));
$server->start();
