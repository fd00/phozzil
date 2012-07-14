<?php

namespace phozzil\net;

use phozzil\io\IOException;

class MultiServer
{
    private $listeners; // MultiServerListener[]

    public function __construct()
    {
        $this->listeners = array();
    }

    public function addListener(MultiServerListener $listener)
    {
        $index = array_search($listener, $this->listeners);
        if ($index !== false) {
            throw new InvalidArgumentException('listener is already added');
        }
        $this->listeners[] = $listener;
    }

    public function start()
    {
        while (true) {
            $observedSockets = array();   // resource[]
            $associatedListers = array(); // Map<resource, function>
            foreach ($this->listeners as $listener) {
                $server = $listener->getServerSocket();
                $observedSockets[] = $server;
                $associatedListers[(integer)$server] = function($resource) use($listener)
                {
                    $listener->onConnect($resource);
                };
                foreach ($listener->getSockets() as $socket) {
                    $observedSockets[] = $socket;
                    $associatedListers[(integer)$socket] = function($resource) use($listener)
                    {
                        $listener->onData($resource);
                    };
                }
            }
            $count = stream_select($observedSockets, $write, $expects, 0, 1000);
            if ($count === false) {
                throw new IOException('stream_select failed');
            }
            foreach ($observedSockets as $observedSocket) {
                $associatedListers[(integer)$observedSocket]($observedSocket);
            }
        }
    }
}