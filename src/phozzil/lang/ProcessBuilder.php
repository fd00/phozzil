<?php

namespace phozzil\lang;

use phozzil\io\IOException;
use phozzil\lang\IllegalArgumentException;

/**
 * OS のプロセスを生成するためのクラスです。
 */
final class ProcessBuilder implements \ArrayAccess
{
    /**
     * @var array コマンド (string)
     */
    private $command;

    /**
     * @var string プロセスの作業ディレクトリ
     */
    private $directory;

    /**
     * @var array 環境変数 (<string, string>)
     */
    private $environment;

    /**
     * OS のプロセスを生成するためのビルダーをインスタンス化します。
     * @param array $command コマンド文字配列 (string[])
     */
    public function __construct(array $command)
    {
        $this->command = implode(' ', $command);
        $this->directory = null;
        $this->environment = $_ENV;
    }

    /**
     * プロセスの作業ディレクトリを指定します。
     * @param string $directory プロセスの作業ディレクトリ
     * @throws IllegalArgumentException 引数に文字列以外を渡した場合
     */
    public function setDirectory($directory)
    {
        if (!is_string($directory)) {
            throw new IllegalArgumentException('directory must be string');
        }
        $this->directory = $directory;
    }

    /**
     * プロセスの作業ディレクトリを返します。
     * @return string プロセスの作業ディレクトリ
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * OS の機能を使用してプロセスを起動します。
     * @throws IOException proc_open が失敗した場合
     * @return Process プロセスインスタンス
     */
    public function start()
    {
        $resource = proc_open($this->command, array(
                        array('pipe', 'r'),
                        array('pipe', 'w'),
                        array('pipe', 'w'),
        ), $pipes, $this->directory, $this->environment);

        if ($resource === false) {
            throw new IOException('proc_open failed');
        }

        return new Process($resource, $pipes);
    }

    /**
     * 指定された環境変数が存在するかを返します。
     * @param string $offset 環境変数
     * @throws IllegalArgumentException 引数が文字列以外である場合
     * @return boolean 指定された環境変数が存在するかどうか
     */
    public function offsetExists($offset)
    {
        if (!is_string($offset)) {
            throw new IllegalArgumentException('key must be string');
        }
        return array_key_exists($offset, $this->environment);
    }

    /**
     * 指定された環境変数を返します。
     * @param string $offset 環境変数
     * @throws IllegalArgumentException 引数が文字列以外である場合
     * @return string 環境変数の値 (存在しない場合は空文字列)
     */
    public function offsetGet($offset)
    {
        if (!is_string($offset)) {
            throw new IllegalArgumentException('key must be string');
        }
        return $this->offsetExists($offset) ? $this->environment[$offset] : '';
    }

    /**
     * 指定された環境変数を設定します。
     * @param string $offset 環境変数
     * @param string $value 値
     * @throws IllegalArgumentException 引数が文字列以外である場合
     */
    public function offsetSet($offset, $value)
    {
        if (!is_string($offset)) {
            throw new IllegalArgumentException('key must be string');
        }
        if (!is_string($value)) {
            throw new IllegalArgumentException('value must be string');
        }
        $this->environment[$offset] = $value;
    }

    /**
     * 指定された環境変数を破棄します。
     * @param string $offset 環境変数
     * @throws IllegalArgumentException 引数が文字列以外である場合
     */
    public function offsetUnset($offset)
    {
        if (!is_string($offset)) {
            throw new IllegalArgumentException('key must be string');
        }
        unset($this->environment[$offset]);
    }
}