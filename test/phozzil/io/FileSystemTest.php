<?php

use phozzil\io\FileSystem;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function delete()
    {
        $root = sys_get_temp_dir();
        $this->assertFileNotExists(FileSystem::join($root, 'dir1'));
        mkdir(FileSystem::join($root, 'dir1', 'dir2'), 0777, true);
        touch(FileSystem::join($root, 'dir1', 'dir2', 'file3'));
        FileSystem::delete(FileSystem::join($root, 'dir1'));
        $this->assertFileNotExists(FileSystem::join($root, 'dir1'));
    }
}