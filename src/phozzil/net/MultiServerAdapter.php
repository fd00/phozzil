<?php

namespace phozzil\net;

use phozzil\lang\IllegalArgumentException;
use phozzil\io\IOException;
use phozzil\net\MultiServerListener;

/**
 * MultiServer ���K�v�Ƃ��� Listener �C���^�t�F�[�X�̋�̎����������ۃN���X�ł��B
 */
abstract class MultiServerAdapter implements MultiServerListener
{
    private $server;  // resource
    private $sockets; // resource[]

    /**
     * MultiServer ����������n���h���C���X�^���X�𐶐����܂��B
     * @param int $port �҂��󂯂�|�[�g�ԍ�
     * @throws IOException �����̃\�P�b�g���\�[�X���m�ۂł��Ȃ������ꍇ
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
     * ���̎����ł͉������܂���B
     * @see phozzil\net\MultiServerListener::onConnect()
     */
    public function onConnect($resource) {}

    /**
     * ���̎����ł͉������܂���B
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