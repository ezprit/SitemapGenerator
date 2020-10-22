<?php

namespace SitemapGenerator\Tests\Dumper;

use PHPUnit\Framework\TestCase;

abstract class FileTestCase extends TestCase
{
    private $file;
    private $otherFile;
    private $nonWriteableFile;

    protected $dumper;

    abstract protected function createDumper();

    public function setUp(): void
    {
        $this->file = sys_get_temp_dir() . '/dummy_file';
        $this->otherFile = sys_get_temp_dir() . '/other_file';
        $this->nonWriteableFile = sys_get_temp_dir() . '/non_writeable_file';

        touch($this->nonWriteableFile);
        chmod($this->nonWriteableFile, 0400);

        $this->dumper = $this->createDumper();
    }

    public function tearDown(): void
    {
        file_exists($this->file) && unlink($this->file);
        file_exists($this->otherFile) && unlink($this->otherFile);
        file_exists($this->nonWriteableFile) && unlink($this->nonWriteableFile);
    }

    protected function nonWriteableFile(): string
    {
        return $this->nonWriteableFile;
    }

    protected function dummyFile(): string
    {
        return $this->file;
    }

    protected function otherDummyFile(): string
    {
        return $this->otherFile;
    }
}
