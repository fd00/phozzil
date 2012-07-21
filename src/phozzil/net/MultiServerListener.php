<?php

namespace phozzil\net;

/**
 * MultiServer が発行するイベントを受け取るためのインタフェースです。
 */
interface MultiServerListener
{
    /**
     * 接続された時に呼び出されます。
     * @param resource $resource
     */
    function onConnect($resource);

    /**
     * データを受信した時に呼び出されます。
     * @param resource $resource
     */
    function onData($resource);

    /**
     * サーバとなる resource を返します。
     * @return resource resource
     */
    function getServerSocket();

    /**
     * クライアントとの端点となる resource の配列を返します。
     * @return array resources
     */
    function getSockets();
}