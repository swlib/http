<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/4/19 下午8:03
 */

namespace Swlib\Tests\Http;

use PHPUnit\Framework\TestCase;
use Swlib\Http\BufferStream;

class BufferStreamTest extends TestCase
{

    public function testHasMetadata()
    {
        $b = new BufferStream(10);
        $this->assertTrue($b->isReadable());
        $this->assertTrue($b->isWritable());
        $this->assertTrue($b->isSeekable());
    }

    public function testRemovesReadDataFromBuffer()
    {
        $b = new BufferStream();
        $this->assertEquals(3, $b->write('foo')->getSize());
        $this->assertFalse($b->eof());
        $this->assertEquals('foo', $b->read(10));
        $this->assertTrue($b->eof());
        $this->assertEquals('', $b->read(10));
    }

    public function testCanCastToStringOrGetContents()
    {
        $b = new BufferStream();
        $b->write('foo');
        $b->write('baz');
        $b->rewind();
        $this->assertEquals('foo', $b->read(3));
        $b->write('bar');
        $this->assertEquals('foobar', (string)$b);
        $this->assertEquals(6, $b->tell());
        $this->assertTrue($b->eof());
    }

}
