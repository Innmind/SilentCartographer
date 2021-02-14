<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Filesystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Filesystem\Directory,
    OperatingSystem\Filesystem\UnsourcedDirectory,
    OperatingSystem\Filesystem\File as DecoratedFile,
    SendActivity,
    Room\Program\Activity\Filesystem\FileLoaded,
};
use Innmind\Filesystem\{
    Adapter,
    Source,
    Directory as DirectoryInterface,
};
use Innmind\Url\Path as RealPath;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};
use Fixtures\Innmind\Filesystem\{
    Directory as Fixture,
    File,
};
use Fixtures\Innmind\Url\Path;
use Properties\Innmind\Filesystem\Directory as Properties;

class DirectoryTest extends TestCase
{
    use BlackBox;

    public function testHoldProperties()
    {
        $this
            ->forAll(
                Properties::properties(),
                Fixture::any(),
                Path::directories(),
            )
            ->then(function($properties, $inner, $path) {
                $properties->ensureHeldBy(Directory::load(
                    $inner,
                    $this->createMock(SendActivity::class),
                    $path,
                ));
            });
    }

    public function testAlwaysReturnFalseWhenCheckingSourceOfUnknownDirectoryOrigin()
    {
        $this
            ->forAll(
                Fixture::any(),
                Path::any(),
                Path::directories(),
            )
            ->then(function($inner, $path, $parent) {
                $directory = Directory::load(
                    $inner,
                    $this->createMock(SendActivity::class),
                    $parent,
                );

                $this->assertFalse($directory->sourcedAt(
                    $this->createMock(Adapter::class),
                    $path,
                ));
            });
    }

    public function testSourcedAt()
    {
        $this
            ->forAll(
                Fixture::any(),
                Path::any(),
                Path::any(),
                Path::directories(),
            )
            ->then(function($inner, $pathA, $pathB, $parent) {
                $adapter = $this->createMock(Adapter::class);
                $directory = Directory::load(
                    new DirectoryInterface\Source(
                        $inner,
                        $adapter,
                        $pathA,
                    ),
                    $this->createMock(SendActivity::class),
                    $parent,
                );

                $this->assertTrue($directory->sourcedAt($adapter, $pathA));
                $this->assertFalse($directory->sourcedAt($adapter, $pathB));
                $this->assertFalse($directory->sourcedAt(
                    $this->createMock(Adapter::class),
                    $pathA,
                ));
            });
    }

    public function testSendActivityWhenLoadingDirectory()
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
                    ->with($this->callback(static function(FileLoaded $activity) use ($path, $inner) {
                        return $activity->path()->toString() === $path->toString().$inner->name()->toString().'/';
                    }));

                Directory::load($inner, $send, $path);
            });
    }

    public function testContentIsNotAltered()
    {
        $this
            ->forAll(
                Fixture::any(),
                Path::directories(),
            )
            ->then(function($inner, $path) {
                $file = Directory::load(
                    $inner,
                    $this->createMock(SendActivity::class),
                    $path,
                );

                $this->assertSame($inner->name(), $file->name());
                $this->assertSame($inner->content(), $file->content());
                $this->assertSame($inner->mediaType(), $file->mediaType());
            });
    }

    public function testAddingFileDoesntSendActivity()
    {
        $this
            ->forAll(
                Fixture::any(),
                File::any(),
                Path::directories(),
            )
            ->then(function($inner, $file, $path) {
                $send = $this->createMock(SendActivity::class);
                $send
                    ->expects($this->once())
                    ->method('__invoke');

                $directory = Directory::load($inner, $send, $path)->add($file);
                $this->assertInstanceOf(UnsourcedDirectory::class, $directory);
            });
    }

    public function testReplaceAtConserveDecoration()
    {
        $this
            ->forAll(
                Fixture::any(),
                Path::any(),
                File::any()
            )
            ->then(function($inner, $path, $file) {
                $send = $this->createMock(SendActivity::class);
                $send
                    ->expects($this->once())
                    ->method('__invoke');
                $directory = Directory::load($inner, $send, $path)->replaceAt(
                    RealPath::of('/'),
                    $file,
                );

                $this->assertInstanceOf(UnsourcedDirectory::class, $directory);
            });
    }

    public function testForeachDecorateFiles()
    {
        $this
            ->forAll(
                Fixture::any(),
                Path::any(),
            )
            ->then(function($inner, $path) {
                $directory = Directory::load(
                    $inner,
                    $this->createMock(SendActivity::class),
                    $path,
                );

                $directory->foreach(function($file) {
                    $this->assertThat(
                        $file,
                        $this->logicalOr(
                            $this->isInstanceOf(Directory::class),
                            $this->isInstanceOf(DecoratedFile::class),
                        ),
                    );
                });
            });
    }

    public function testFilterDecorateFiles()
    {
        $this
            ->forAll(
                Fixture::any(),
                Path::any(),
            )
            ->then(function($inner, $path) {
                $directory = Directory::load(
                    $inner,
                    $this->createMock(SendActivity::class),
                    $path,
                );

                $directory->filter(function($file) {
                    $this->assertThat(
                        $file,
                        $this->logicalOr(
                            $this->isInstanceOf(Directory::class),
                            $this->isInstanceOf(DecoratedFile::class),
                        ),
                    );

                    return true;
                });
            });
    }

    public function testReduceDecorateFiles()
    {
        $this
            ->forAll(
                Fixture::any(),
                Path::any(),
            )
            ->then(function($inner, $path) {
                $directory = Directory::load(
                    $inner,
                    $this->createMock(SendActivity::class),
                    $path,
                );

                $directory->reduce(null, function($carry, $file) {
                    $this->assertThat(
                        $file,
                        $this->logicalOr(
                            $this->isInstanceOf(Directory::class),
                            $this->isInstanceOf(DecoratedFile::class),
                        ),
                    );
                });
            });
    }
}
