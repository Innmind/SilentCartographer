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
    Name,
};
use Innmind\Url\Path;
use Innmind\Stream\Readable;
use Innmind\Immutable\Set;
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
                Path::none()
            )
        );
    }

    public function testAdd()
    {
        $adapter = new Adapter(
            $inner = $this->createMock(AdapterInterface::class),
            $send = $this->createMock(SendActivity::class),
            Path::of('/tmp/')
        );
        $file = File\File::named(
            'foo',
            $this->createMock(Readable::class)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new FilePersisted(Path::of('/tmp/foo')));
        $inner
            ->expects($this->once())
            ->method('add')
            ->with($file);

        $this->assertNull($adapter->add($file));
    }

    public function testGet()
    {
        $adapter = new Adapter(
            $inner = $this->createMock(AdapterInterface::class),
            $send = $this->createMock(SendActivity::class),
            Path::of('/tmp/')
        );
        $file = File\File::named(
            'foo',
            $this->createMock(Readable::class)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new FileLoaded(Path::of('/tmp/foo')));
        $inner
            ->expects($this->once())
            ->method('get')
            ->with(new Name('foo'))
            ->willReturn($file);

        $this->assertSame($file, $adapter->get(new Name('foo')));
    }

    public function testHas()
    {
        $adapter = new Adapter(
            $inner = $this->createMock(AdapterInterface::class),
            $send = $this->createMock(SendActivity::class),
            Path::of('/tmp/')
        );
        $file = File\File::named(
            'foo',
            $this->createMock(Readable::class)
        );
        $send
            ->expects($this->never())
            ->method('__invoke');
        $inner
            ->expects($this->exactly(2))
            ->method('contains')
            ->with(new Name('foo'))
            ->will($this->onConsecutiveCalls(false, true));

        $this->assertFalse($adapter->contains(new Name('foo')));
        $this->assertTrue($adapter->contains(new Name('foo')));
    }

    public function testRemove()
    {
        $adapter = new Adapter(
            $inner = $this->createMock(AdapterInterface::class),
            $send = $this->createMock(SendActivity::class),
            Path::of('/tmp/')
        );
        $file = File\File::named(
            'foo',
            $this->createMock(Readable::class)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new FileRemoved(Path::of('/tmp/foo')));
        $inner
            ->expects($this->once())
            ->method('remove')
            ->with(new Name('foo'));

        $this->assertNull($adapter->remove(new Name('foo')));
    }

    public function testAll()
    {
        $adapter = new Adapter(
            $inner = $this->createMock(AdapterInterface::class),
            $send = $this->createMock(SendActivity::class),
            Path::of('/tmp/')
        );
        $file = File\File::named(
            'foo',
            $this->createMock(Readable::class)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new FileLoaded(Path::of('/tmp/foo')));
        $inner
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                $all = Set::of(File::class, $file)
            );

        $this->assertSame($all, $adapter->all());
    }
}
