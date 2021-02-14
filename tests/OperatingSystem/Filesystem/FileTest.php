<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Filesystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Filesystem\File,
    SendActivity,
};
use Innmind\Filesystem\{
    Source,
    Adapter,
};
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};
use Fixtures\Innmind\Filesystem\{
    File as Fixture,
    Name,
};
use Fixtures\Innmind\Url\Path;

class FileTest extends TestCase
{
    use BlackBox;

    public function testContentIsNotAltered()
    {
        $this
            ->forAll(
                Fixture::any(),
                Path::any(),
            )
            ->then(function($inner, $path) {
                $file = new File(
                    $inner,
                    $this->createMock(SendActivity::class),
                    $path,
                );

                $this->assertSame($inner->name(), $file->name());
                $this->assertSame($inner->content(), $file->content());
                $this->assertSame($inner->mediaType(), $file->mediaType());
            });
    }

    public function testActivityMessageContainsBothFolderAndFileNames()
    {
        $this
            ->forAll(
                Fixture::any(),
                Path::directories(),
            )
            ->then(function($inner, $path) {
                $send = $this->createMock(SendActivity::class);
                $send
                    ->expects($this->once())
                    ->method('__invoke')
                    ->with($this->callback(static function($activity) use ($inner, $path) {
                        return $activity->path()->toString() === $path->toString().$inner->name()->toString();
                    }));

                new File($inner, $send, $path);
            });
    }

    public function testFileFromUnknownOriginWillAlwaysReturnFalseToSourcedAt()
    {
        $this
            ->forAll(
                Fixture::any(),
                Path::any(),
                Path::directories(),
            )
            ->then(function($inner, $path, $folder) {
                $file = new File(
                    $inner,
                    $this->createMock(SendActivity::class),
                    $folder,
                );

                $this->assertFalse($file->sourcedAt(
                    $this->createMock(Adapter::class),
                    $path,
                ));
            });
    }

    public function testSourcedAt()
    {
        $this
            ->forAll(
                Set\Elements::of(true, false),
                Path::any(),
                Path::directories(),
                Name::any(),
            )
            ->then(function($expected, $path, $folder, $name) {
                $inner = $this->createMock(Source::class);
                $inner
                    ->expects($this->once())
                    ->method('sourcedAt')
                    ->willReturn($expected);
                $inner
                    ->expects($this->any())
                    ->method('name')
                    ->willReturn($name);
                $file = new File(
                    $inner,
                    $this->createMock(SendActivity::class),
                    $folder,
                );

                $this->assertSame(
                    $expected,
                    $file->sourcedAt(
                        $this->createMock(Adapter::class),
                        $path,
                    ),
                );
            });
    }
}
