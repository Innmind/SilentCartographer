<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Filesystem;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Filesystem\WatchingPath,
    Activity,
};
use Innmind\Url\Path;
use PHPUnit\Framework\TestCase;

class WatchingPathTest extends TestCase
{
    public function testInterface()
    {
        $activity = new WatchingPath(
            $path = Path::of('foo')
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'filesystem'], $activity->tags()->list());
        $this->assertSame($path, $activity->path());
        $this->assertSame('Watching path: foo', $activity->toString());
    }
}
