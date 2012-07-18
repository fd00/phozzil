<?php

namespace phozzil\net;

/**
 * MultiServer �����s����C�x���g���󂯎�邽�߂̃C���^�t�F�[�X�ł��B
 */
interface MultiServerListener
{
    /**
     * �ڑ����ꂽ���ɌĂяo����܂��B
     * @param resource $resource
     */
    function onConnect($resource);

    /**
     * �f�[�^����M�������ɌĂяo����܂��B
     * @param resource $resource
     */
    function onData($resource);

    /**
     * �T�[�o�ƂȂ� resource ��Ԃ��܂��B
     * @return resource resource
     */
    function getServerSocket();

    /**
     * �N���C�A���g�Ƃ̒[�_�ƂȂ� resource �̔z���Ԃ��܂��B
     * @return array resources
     */
    function getSockets();
}