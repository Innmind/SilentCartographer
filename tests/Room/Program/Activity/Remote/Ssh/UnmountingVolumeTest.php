<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Remote\Ssh\UnmountingVolume,
    Activity,
};
use Innmind\Server\Control\Server\Volumes\Name;
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class UnmountingVolumeTest extends TestCase
{
    public function testInterface()
    {
        $activity = new UnmountingVolume(
            Url::of('ssh://user:pwd@example.com:22/')->authority(),
            new Name('foo'),
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'remote', 'ssh', 'control', 'volume'], $activity->tags()->list());
        $this->assertSame('Unmounting volume: [user:pwd@example.com:22] foo', $activity->toString());
    }
}
