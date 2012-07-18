<?php

namespace phozzil\net;

use phozzil\lang\IllegalArgumentException;
use phozzil\io\IOException;

/**
 * 複数の port で待ち受けることのできるサーバアプリケーションを実装するためのクラスです。
 */
class MultiServer
{
    private $listeners; // MultiServerListener[]
    private $timeout;   // integer

    /**
     * サーバアプリケーションのインスタンスを生成します。
     * @param int $timeout タイムアウトの時間 (マイクロ秒)
     */
    public function __construct($timeout = 1000)
    {
        $this->listeners = array();
        $this->setTimeout($timeout);
    }

    /**
     * 待ち受ける port とハンドラを実装したリスナーを追加します。
     * @param MultiServerListener $listener リスナーインスタンス
     * @throws IllegalArgumentException 登録済みのリスナーを指定した場合
     */
    public function addListener(MultiServerListener $listener)
    {
        $index = array_search($listener, $this->listeners);
        if ($index !== false) {
            throw new IllegalArgumentException('listener is already added');
        }
        $this->listeners[] = $listener;
    }

    /**
     * 待ち受け処理一回あたりの制限時間を設定します。
     * @param int $timeout タイムアウトの時間 (マイクロ秒)
     * @throws IllegalArgumentException 整数型以外または負の整数を渡した場合
     */
    public function setTimeout($timeout)
    {
        if (!is_int($timeout)) {
            throw new IllegalArgumentException('timeout must be integer');
        }
        if ($timeout <= 0) {
            throw new IllegalArgumentException('timeout > 0');
        }
        $this->timeout = $timeout;
    }

    /**
     * 待ち受けるループに入ります。
     * @throws IOException 待ち受け処理中に何かしらの例外が発生した場合
     */
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
            $count = stream_select($observedSockets, $write, $except, $this->timeout / 1000000, $this->timeout % 1000000);
            if ($count === false) {
                throw new IOException('stream_select failed');
            }
            foreach ($observedSockets as $observedSocket) {
                $associatedListers[(integer)$observedSocket]($observedSocket);
            }
        }
    }
}