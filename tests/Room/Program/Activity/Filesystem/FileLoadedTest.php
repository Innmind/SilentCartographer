<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Filesystem;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Filesystem\FileLoaded,
    Activity,
};
use Innmind\Url\Path;
use PHPUnit\Framework\TestCase;

class FileLoadedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new FileLoaded(
            $path = Path::of('foo')
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'filesystem'], \iterator_to_array($activity->tags()));
        $this->assertSame($path, $activity->path());
        $this->assertSame('File loaded: foo', (string) $activity);
    }
}
