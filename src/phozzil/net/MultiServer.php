<?php

namespace phozzil\net;

use phozzil\lang\IllegalArgumentException;
use phozzil\io\IOException;

/**
 * ������ port �ő҂��󂯂邱�Ƃ̂ł���T�[�o�A�v���P�[�V�������������邽�߂̃N���X�ł��B
 */
class MultiServer
{
    private $listeners; // MultiServerListener[]
    private $timeout;   // integer

    /**
     * �T�[�o�A�v���P�[�V�����̃C���X�^���X�𐶐����܂��B
     * @param int $timeout �^�C���A�E�g�̎��� (�}�C�N���b)
     */
    public function __construct($timeout = 1000)
    {
        $this->listeners = array();
        $this->setTimeout($timeout);
    }

    /**
     * �҂��󂯂� port �ƃn���h���������������X�i�[��ǉ����܂��B
     * @param MultiServerListener $listener ���X�i�[�C���X�^���X
     * @throws IllegalArgumentException �o�^�ς݂̃��X�i�[���w�肵���ꍇ
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
     * �҂��󂯏�����񂠂���̐������Ԃ�ݒ肵�܂��B
     * @param int $timeout �^�C���A�E�g�̎��� (�}�C�N���b)
     * @throws IllegalArgumentException �����^�ȊO�܂��͕��̐�����n�����ꍇ
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
     * �҂��󂯂郋�[�v�ɓ���܂��B
     * @throws IOException �҂��󂯏������ɉ�������̗�O�����������ꍇ
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