<?php

namespace SitemapGenerator\Tests\Dumper;

use SitemapGenerator\Dumper\File;

class FileTest extends FileTestCase
{
    public function testDumper()
    {
        $dumper = new File($this->file);

        $dumper->dump('joe');
        $this->assertTrue(file_exists($this->file));

        $this->assertSame('joe', file_get_contents($this->file));

        $dumper->dump('-hell yeah!');
        $this->assertSame('joe-hell yeah!', file_get_contents($this->file));
    }
}
