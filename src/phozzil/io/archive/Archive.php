<?php

namespace phozzil\io\archive;

use phozzil\io\FileNotFoundException;
use phozzil\io\FileSystem;
use phozzil\io\IOException;
use phozzil\lang\IllegalArgumentException;

require_once 'File/Archive.php';
require_once 'File/Archive/Writer/Files.php';

class Archive
{
    /**
     * @var string ソルト
     */
    static public $SALT = 'phozzil.io.archive';

    /**
     * インスタンス化できません。
     */
    private function __construct()
    {
    }

    /**
     * 指定されたアーカイブをテンポラリディレクトリ配下に展開し、その内容のパスの一覧を返します。
     * アーカイブにアーカイブが含まれる場合は再帰的に展開します。
     * 展開されたファイルは終了後に削除されます。
     *
     * @todo File_Archive インスタンスを複数回つくらなくてもいい方法を考える。
     *
     * @param string $archivePath アーカイブのパス
     * @param string $destinationDirectory アーカイブの展開先のディレクトリのパス (指定されていない場合はシステムのテンポラリディレクトリに生成した一時ディレクトリ配下)
     * @return array 展開後のファイルパスの一覧
     */
    static public function extractArchive($archivePath, $destinationDirectory = null)
    {
        if (!file_exists($archivePath)) {
            throw new FileNotFoundException($archivePath);
        }
        $pathinfo = pathinfo($archivePath);
        if (is_null($destinationDirectory)) {
            $destinationDirectory = FileSystem::join(FileSystem::getTemporaryRoot(), md5(self::$SALT . ':' . $pathinfo['basename']));
        }
        $extension = $pathinfo['extension'];

        $writer = new \File_Archive_Writer_Files($destinationDirectory);
        FileSystem::deleteOnExit($destinationDirectory);

        $source = \File_Archive::_convertToReader($archivePath);
        $reader = \File_Archive::readArchive($extension, $source);
        $fileList = $reader->getFileList();

        // getFileList() の中で close() してすべてのリソースを破棄してしまうため再生成する必要がある。
        $source = \File_Archive::_convertToReader($archivePath);
        $reader = \File_Archive::readArchive($extension, $source);
        $pear = new \PEAR();
        if ($pear->isError(\File_Archive::extract($reader, $writer))) {
            throw new IOException('archive cannot be extracted');
        }

        $contents = array();
        foreach ($fileList as $filePath) {
            $content = FileSystem::join($destinationDirectory, $filePath);
            if (is_file($content)) {
                $contentPathInfo = pathinfo($content);
                if (\File_Archive::isKnownExtension($contentPathInfo['extension'])) {
                    // 展開後のファイルがアーカイブである場合はそこをルートディレクトリとして再帰的に展開する。
                    $subDirectory = FileSystem::join($contentPathInfo['dirname'],
                                    md5(self::$SALT . ':' . basename($filePath)));
                    $contents = array_merge($contents, self::extractArchive($content, $subDirectory));
                } else {
                    $contents[] = $content;
                }
            }
        }
        return $contents;
    }
}