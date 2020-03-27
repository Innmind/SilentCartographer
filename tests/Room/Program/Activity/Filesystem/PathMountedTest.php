<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Filesystem;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Filesystem\PathMounted,
    Activity,
};
use Innmind\Url\Path;
use PHPUnit\Framework\TestCase;

class PathMountedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new PathMounted(
            $path = Path::of('foo')
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'filesystem'], \iterator_to_array($activity->tags()));
        $this->assertSame($path, $activity->path());
        $this->assertSame('Path mounted: foo', (string) $activity);
    }
}
