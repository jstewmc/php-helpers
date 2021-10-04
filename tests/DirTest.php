<?php

namespace Jstewmc\PhpHelpers;

use org\bovigo\vfs\{vfsStream, vfsStreamDirectory};

class DirTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var  vfsStreamDirectory
     */
    private $root;


    public function setUp(): void
    {
        $this->root = vfsStream::setup('root');
    }

    public function testAbs2RelReturnsAbsoluteWhenBaseIsEmpty(): void
    {
        $input = 'path/to/foo';

        $this->assertEquals(Dir::abs2rel($input, ''), $input);
    }

    public function testAbs2RelReturnsAbsoluteWhenBaseDoesNotMatch(): void
    {
        $input = 'path/to/foo';

        $this->assertEquals(Dir::abs2rel($input, 'bar'), $input);
    }

    public function testAbs2RelReturnsRelativeWhenBaseDoesMatch(): void
    {
        $input    = 'path/to/foo';
        $actual   = Dir::abs2rel($input, 'path/to');
        $expected = 'foo';

        $this->assertEquals($actual, $expected);
    }

    public function testCopyThrowsInvalidArgumentExceptionWhenModeIsNotIntegerAndNotFalse(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $source = vfsStream::newDirectory('foo')->at($this->root);

        $destination = vfsStream::newDirectory('bar')->at($this->root);

        Dir::copy($source->url(), $destination->url(), 'foo');
    }

    public function testCopyThrowsInvalidArgumentExceptionWhenSourceDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $destination = vfsStream::newDirectory('bar')->at($this->root);

        Dir::copy("{$this->root->url()}/foo", $destination->url());
    }

    public function testCopyThrowsInvalidArgumentExceptionWhenSourceIsNotReadable(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $source = vfsStream::newDirectory('foo', 000)->at($this->root);

        $destination = vfsStream::newDirectory('bar')->at($this->root);

        Dir::copy($source->url(), $destination->url());
    }

    public function testCopyThrowsInvalidArgumentExceptionWhenSourceIsNotADirectory(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // note, it's a file
        $source = vfsStream::newFile('foo.txt')->at($this->root);

        $destination = vfsStream::newDirectory('bar')->at($this->root);

        Dir::copy($source->url(), $destination->url());
    }

    public function testCopyThrowsInvalidArgumentExceptionWhenDestinationDoesNotExistAndModeIsFalse(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $source = vfsStream::newDirectory('foo')->at($this->root);

        Dir::copy($source->url(), "{$this->root->url()}/bar", false);
    }

    public function testCopyThrowsInvalidArgumentExceptionWhenDestinationIsNotADirectory(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $source = vfsStream::newDirectory('foo')->at($this->root);

        // note, it's a file
        $destination = vfsStream::newFile('bar.txt')->at($this->root);

        Dir::copy($source->url(), $destination->url());
    }

    public function testCopyThrowsInvalidArgumentExceptionWhenDestinationIsNotReadable(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $source = vfsStream::newDirectory('foo')->at($this->root);

        $destination = vfsStream::newDirectory('bar', 000)->at($this->root);

        Dir::copy($source->url(), $destination->url());
    }

    public function testCopyReturnsTrueWhenDirectoryIsEmpty(): void
    {
        $source = vfsStream::newDirectory('foo')->at($this->root);

        $destination = vfsStream::newDirectory('bar')->at($this->root);

        $this->assertTrue(Dir::copy($source->url(), $destination->url()));
    }

    public function testCopyReturnsTrueWhenDirectoryIsNotEmpty(): void
    {
        $source = vfsStream::newDirectory('foo')->at($this->root);

        $file1 = vfsStream::newFile('foo.txt')->at($source);

        $subdirectory = vfsStream::newDirectory('bar')->at($source);

        $file2 = vfsStream::newFile('bar.txt')->at($subdirectory);

        $destination = vfsStream::newDirectory('baz')->at($this->root);

        $this->assertTrue(Dir::copy($source->url(), $destination->url()));

        // assert the old files still exist
        $this->assertTrue(is_dir($source->url()));
        $this->assertTrue(is_dir($subdirectory->url()));
        $this->assertTrue(is_file($file1->url()));
        $this->assertTrue(is_file($file2->url()));

        // assert the new files exist
        $this->assertTrue(is_dir($destination->url()));
        $this->assertTrue(is_file("{$destination->url()}/foo.txt"));
        $this->assertTrue(is_dir("{$destination->url()}/bar"));
        $this->assertTrue(is_file("{$destination->url()}/bar/bar.txt"));
    }

    public function testRemoveThrowsInvalidArgumentExceptionWhenDirectoryDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Dir::remove("{$this->root->url()}/foo", $this->root->url());
    }

    public function testRemoveThrowsInvalidArgumentExceptionWhenDirectoryIsNotReadable(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $directory = vfsStream::newDirectory('foo', 000)->at($this->root);

        Dir::remove($directory->url(), $this->root->url());
    }

    public function testRemoveThrowsInvalidArgumentExceptionWhenDirectoryIsNotContained(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $directory = vfsStream::newDirectory('foo')->at($this->root);

        Dir::remove($directory->url(), '/path/to/container');
    }

    public function testRemoveReturnsTrueWhenDirectoryIsEmpty(): void
    {
        $directory = vfsStream::newDirectory('foo')->at($this->root);

        $this->assertTrue(Dir::remove($directory->url(), $this->root->url()));

        $this->assertFalse(is_dir($directory->url()));
    }

    public function testRemoveReturnsTrueWhenDirectoryIsNotEmpty(): void
    {
        $directory = vfsStream::newDirectory('foo')->at($this->root);

        $file1 = vfsStream::newFile('foo.txt')->at($directory);

        $subdirectory = vfsStream::newDirectory('bar')->at($directory);

        $file2 = vfsStream::newFile('bar.txt')->at($subdirectory);

        $this->assertTrue(Dir::remove($directory->url(), $this->root->url()));

        // assert the files and directories are gone
        $this->assertFalse(file_exists($file2->url()));
        $this->assertFalse(is_dir($subdirectory->url()));
        $this->assertFalse(file_exists($file1->url()));
        $this->assertFalse(is_dir($directory->url()));
    }
}
