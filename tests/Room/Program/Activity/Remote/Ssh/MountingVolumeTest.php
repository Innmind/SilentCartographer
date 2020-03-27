<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Remote\Ssh\MountingVolume,
    Activity,
};
use Innmind\Server\Control\Server\Volumes\Name;
use Innmind\Url\{
    Path,
    Url,
};
use PHPUnit\Framework\TestCase;

class MountingVolumeTest extends TestCase
{
    public function testInterface()
    {
        $activity = new MountingVolume(
            Url::of('ssh://user:pwd@example.com:22/')->authority(),
            new Name('foo'),
            Path::of('/somewhere'),
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'remote', 'ssh', 'control', 'volume'], $activity->tags()->list());
        $this->assertSame('Mounting volume: [user:pwd@example.com:22] foo@/somewhere', $activity->toString());
    }
}
