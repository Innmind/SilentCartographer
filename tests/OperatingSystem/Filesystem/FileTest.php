<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Filesystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Filesystem\File,
    SendActivity,
};
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};
use Fixtures\Innmind\Filesystem\File as Fixture;
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
                    ->with($this->callback(function($activity) use ($inner, $path) {
                        return $activity->path()->toString() === $path->toString().$inner->name()->toString();
                    }));

                new File($inner, $send, $path);
            });
    }
}
