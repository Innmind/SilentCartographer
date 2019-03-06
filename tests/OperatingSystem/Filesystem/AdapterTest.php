<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Filesystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Filesystem\Adapter,
    SendActivity,
    Room\Program\Activity\Filesystem\FilePersisted,
    Room\Program\Activity\Filesystem\FileLoaded,
    Room\Program\Activity\Filesystem\FileRemoved,
};
use Innmind\Filesystem\{
    Adapter as AdapterInterface,
    File,
};
use Innmind\Url\{
    PathInterface,
    Path,
};
use Innmind\Stream\Readable;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class AdapterTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            AdapterInterface::class,
            new Adapter(
                $this->createMock(AdapterInterface::class),
                $this->createMock(SendActivity::class),
                $this->createMock(PathInterface::class)
            )
        );
    }

    public function testAdd()
    {
        $adapter = new Adapter(
            $inner = $this->createMock(AdapterInterface::class),
            $send = $this->createMock(SendActivity::class),
            new Path('/tmp/')
        );
        $file = new File\File(
            'foo',
            $this->createMock(Readable::class)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new FilePersisted(new Path('/tmp/foo')));
        $inner
            ->expects($this->once())
            ->method('add')
            ->with($file)
            ->will($this->returnSelf());

        $this->assertSame($adapter, $adapter->add($file));
    }

    public function testGet()
    {
        $adapter = new Adapter(
            $inner = $this->createMock(AdapterInterface::class),
            $send = $this->createMock(SendActivity::class),
            new Path('/tmp/')
        );
        $file = new File\File(
            'foo',
            $this->createMock(Readable::class)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new FileLoaded(new Path('/tmp/foo')));
        $inner
            ->expects($this->once())
            ->method('get')
            ->with('foo')
            ->willReturn($file);

        $this->assertSame($file, $adapter->get('foo'));
    }

    public function testHas()
    {
        $adapter = new Adapter(
            $inner = $this->createMock(AdapterInterface::class),
            $send = $this->createMock(SendActivity::class),
            new Path('/tmp/')
        );
        $file = new File\File(
            'foo',
            $this->createMock(Readable::class)
        );
        $send
            ->expects($this->never())
            ->method('__invoke');
        $inner
            ->expects($this->exactly(2))
            ->method('has')
            ->with('foo')
            ->will($this->onConsecutiveCalls(false, true));

        $this->assertFalse($adapter->has('foo'));
        $this->assertTrue($adapter->has('foo'));
    }

    public function testRemove()
    {
        $adapter = new Adapter(
            $inner = $this->createMock(AdapterInterface::class),
            $send = $this->createMock(SendActivity::class),
            new Path('/tmp/')
        );
        $file = new File\File(
            'foo',
            $this->createMock(Readable::class)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new FileRemoved(new Path('/tmp/foo')));
        $inner
            ->expects($this->once())
            ->method('remove')
            ->with('foo')
            ->will($this->returnSelf());

        $this->assertSame($adapter, $adapter->remove('foo'));
    }

    public function testAll()
    {
        $adapter = new Adapter(
            $inner = $this->createMock(AdapterInterface::class),
            $send = $this->createMock(SendActivity::class),
            new Path('/tmp/')
        );
        $file = new File\File(
            'foo',
            $this->createMock(Readable::class)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new FileLoaded(new Path('/tmp/foo')));
        $inner
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                $all = Map::of('string', File::class)('foo', $file)
            );

        $this->assertSame($all, $adapter->all());
    }
}
