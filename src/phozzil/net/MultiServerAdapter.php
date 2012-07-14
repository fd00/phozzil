<?php

namespace phozzil\net;

use phozzil\io\IOException;
use phozzil\net\MultiServerListener;

abstract class MultiServerAdapter implements MultiServerListener
{
    private $server;  // resource
    private $sockets; // resource[]

    public function __construct($port)
    {
        $server = stream_socket_server('tcp://localhost:' . $port);
        if ($server === false) {
            throw new IOException('stream_socket_server failed');
        }
        $this->server = $server;
        $this->sockets = array();
    }

    public function onConnect($resource) {}
    public function onData($resource) {}

    public function getServerSocket()
    {
        return $this->server;
    }

    public function getSockets()
    {
        return $this->sockets;
    }

    public function addSocket($resource)
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException();
        }
        $this->sockets[] = $resource;
    }

    public function removeSocket($resource)
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException();
        }
        $index = array_search($resource, $this->sockets);
        if ($index !== false) {
            unset($this->sockets[$index]);
        }
    }
}