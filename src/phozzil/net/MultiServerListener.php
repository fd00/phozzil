<?php

namespace phozzil\net;

interface MultiServerListener
{
    function onConnect($resource);
    function onData($resource);

    function getServerSocket();
    function getSockets();
}