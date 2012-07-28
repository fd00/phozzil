<?php

namespace phozzil\io;

/**
 * ファイル周りのユーティリティクラスです。
 */
final class FileSystem
{
    /**
     * @var array 処理系終了時に削除されるパスのリスト
     */
    static private $temporaryPaths = array();

    /**
     * インスタンス化できません。
     */
    private function __construct()
    {
    }

    /**
     * 一意なファイル名を生成します。
     *
     * パーミッションは 600 です。
     * @throws IOException 一時ファイルの生成に失敗した場合
     */
    static public function createTemporaryFile()
    {
        $result = tempnam(sys_get_temp_dir(), '');
        if ($result === false) {
            throw new IOException('createTemporaryFile failed');
        }
        self::deleteOnExit($result);
        return $result;
    }

    /**
     * 指定されたパスのファイルまたはディレクトリを削除します。
     *
     * ディレクトリの場合は再帰的に削除します。
     * @param string $path 削除するパス
     */
    static public function delete($path)
    {
        if (is_file($path)) {
            unlink($path);
        } else if (is_dir($path)) {
            foreach (new \DirectoryIterator($path) as $content) {
                if ($content->isDot()) {
                    continue;
                }
                if ($content->isFile()) {
                    unlink($content->getPathName());
                } else if ($content->isDir()) {
                    self::delete($content->getPathName());
                }
            }
            rmdir($path);
        }
    }

    /**
     * 指定されたパスのファイルまたはディレクトリを処理系終了時に削除するようマークします。
     * @param string $path 終了時に削除するパス
     */
    static public function deleteOnExit($path)
    {
        if (!array_key_exists($path, self::$temporaryPaths)) {
            self::$temporaryPaths[] = $path;
        }
    }

    /**
     * 指定された引数群をディレクトリ区切り文字で結合します。
     *
     * @example FileSystem::join('var', 'log', 'kernel.log'); // => Unix の場合は '/var/log/kernel.log'
     */
    static public function join()
    {
        return join(DIRECTORY_SEPARATOR, func_get_args());
    }

    /**
     * 処理系終了時に deleteOnExit でマークされたパスを削除するハンドラです。
     *
     * 直接呼び出すことは推奨されていません。
     * @deprecated
     */
    static public function onExit()
    {
        $temporaryPaths = self::$temporaryPaths;
        if (empty($temporaryPaths)) {
            return;
        }
        foreach ($temporaryPaths as $path) {
            self::delete($path);
        }
    }
}

register_shutdown_function(function()
{
    FileSystem::onExit();
});