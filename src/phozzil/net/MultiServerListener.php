<?php

namespace phozzil\net;

/**
 * MultiServer が発行するイベントを受け取るためのインタフェースです。
 */
interface MultiServerListener
{
    /**
     * 接続された時に呼び出されます。
     * @param resource $resource 接続を確立したソケットリソース
     */
    function onConnect($resource);

    /**
     * データを受信した時に呼び出されます。
     * @param resource $resource データを受信したソケットリソース
     */
    function onData($resource);

    /**
     * 待ち受けサーバとなる resource を返します。
     * @return resource resource
     */
    function getServerSocket();

    /**
     * クライアントとの端点となる resource の配列を返します。
     * @return array resources
     */
    function getSockets();
}