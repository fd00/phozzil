<?php

namespace phozzil\net;

use phozzil\lang\IllegalArgumentException;
use phozzil\io\IOException;
use phozzil\net\MultiServerListener;

/**
 * MultiServer が必要とする Listener インタフェースの空の実装を持つ抽象クラスです。
 */
abstract class MultiServerAdapter implements MultiServerListener
{
    private $server;  // resource
    private $sockets; // resource[]

    /**
     * MultiServer が処理するハンドラインスタンスを生成します。
     * @param int $port 待ち受けるポート番号
     * @throws IOException 内部のソケットリソースが確保できなかった場合
     */
    public function __construct($port)
    {
        $server = stream_socket_server('tcp://localhost:' . $port);
        if ($server === false) {
            throw new IOException('stream_socket_server failed');
        }
        $this->server = $server;
        $this->sockets = array();
    }

    /**
     * この実装では何もしません。
     * @see phozzil\net\MultiServerListener::onConnect()
     */
    public function onConnect($resource) {}

    /**
     * この実装では何もしません。
     * @see phozzil\net\MultiServerListener::onData()
     */
    public function onData($resource) {}

    /**
     * @see phozzil\net\MultiServerListener::getServerSocket()
     */
    public function getServerSocket()
    {
        return $this->server;
    }

    /**
     * @see phozzil\net\MultiServerListener::getSockets()
     */
    public function getSockets()
    {
        return $this->sockets;
    }

    public function addSocket($resource)
    {
        if (!is_resource($resource)) {
            throw new IllegalArgumentException('$resource is not resource');
        }
        $this->sockets[] = $resource;
    }

    public function removeSocket($resource)
    {
        if (!is_resource($resource)) {
            throw new IllegalArgumentException('$resource is not resource');
        }
        $index = array_search($resource, $this->sockets);
        if ($index !== false) {
            unset($this->sockets[$index]);
        }
    }
}