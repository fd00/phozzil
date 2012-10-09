<?php

use phozzil\io\archive\Archive;
use phozzil\io\FileSystem;

class ArchiveTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function extractArchive()
    {
        $archive = 'archive.tgz';
        $archivePath = FileSystem::join(dirname(__FILE__), 'fixture', $archive);

        $pathinfo = pathinfo($archivePath);
        $directory = md5(Archive::$SALT . ':' . $pathinfo['basename']);
        $expected = array(
                        FileSystem::join(FileSystem::getTemporaryRoot(), $directory, 'contents1.txt'),
                        FileSystem::join(FileSystem::getTemporaryRoot(), $directory, 'contents', 'contents2.txt'),
                        FileSystem::join(FileSystem::getTemporaryRoot(), $directory, 'contents', md5(Archive::$SALT . ':inner.zip'), 'contents', 'contents3.txt'),
        );

        $actual = Archive::extractArchive($archivePath);
        $this->assertEquals($expected, $actual);
    }
}