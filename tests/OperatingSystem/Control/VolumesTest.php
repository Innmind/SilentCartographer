<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Control;

use Innmind\SilentCartographer\{
    OperatingSystem\Control\Volumes,
    SendActivity,
};
use Innmind\Server\Control\Server\{
    Volumes as VolumesInterface,
    Volumes\Name,
};
use PHPUnit\Framework\TestCase;
use Fixtures\Innmind\Url\Path;
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
                $this->createMock(SendActivity::class),
            ),
        );
    }

    public function testMount()
    {
        $this
            ->forAll(
                Set\Strings::any(),
                Path::any(),
            )
            ->then(function($name, $path) {
                $volumes = new Volumes(
                    $inner = $this->createMock(VolumesInterface::class),
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
            ->forAll(Set\Strings::atLeast(1))
            ->then(function($name) {
                $volumes = new Volumes(
                    $inner = $this->createMock(VolumesInterface::class),
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
