<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Remote\Ssh;

use Innmind\SilentCartographer\{
    OperatingSystem\Remote\Ssh\Volumes,
    SendActivity,
};
use Innmind\Server\Control\Server\{
    Volumes as VolumesInterface,
    Volumes\Name,
};
use PHPUnit\Framework\TestCase;
use Fixtures\Innmind\Url\{
    Path,
    Authority,
};
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};

class VolumesTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $this->assertInstanceOf(
            VolumesInterface::class,
            new Volumes(
                $this->createMock(VolumesInterface::class),
                $this->seeder()(Authority::any()),
                $this->createMock(SendActivity::class),
            ),
        );
    }

    public function testMount()
    {
        $this
            ->forAll(
                Authority::any(),
                Set\Strings::atLeast(1),
                Path::any(),
            )
            ->then(function($authority, $name, $path) {
                $volumes = new Volumes(
                    $inner = $this->createMock(VolumesInterface::class),
                    $authority,
                    $send = $this->createMock(SendActivity::class),
                );
                $inner
                    ->expects($this->once())
                    ->method('mount')
                    ->with(new Name($name), $path);
                $send
                    ->expects($this->once())
                    ->method('__invoke');

                $this->assertNull($volumes->mount(new Name($name), $path));
            });
    }

    public function testUnount()
    {
        $this
            ->forAll(
                Authority::any(),
                Set\Strings::atLeast(1)
            )
            ->then(function($authority, $name) {
                $volumes = new Volumes(
                    $inner = $this->createMock(VolumesInterface::class),
                    $authority,
                    $send = $this->createMock(SendActivity::class),
                );
                $inner
                    ->expects($this->once())
                    ->method('unmount')
                    ->with(new Name($name));
                $send
                    ->expects($this->once())
                    ->method('__invoke');

                $this->assertNull($volumes->unmount(new Name($name)));
            });
    }
}
