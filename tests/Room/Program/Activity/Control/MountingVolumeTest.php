<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Control;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Control\MountingVolume,
    Activity,
};
use Innmind\Server\Control\Server\Volumes\Name;
use Innmind\Url\Path;
use PHPUnit\Framework\TestCase;

class MountingVolumeTest extends TestCase
{
    public function testInterface()
    {
        $activity = new MountingVolume(
            new Name('foo'),
            Path::of('/somewhere'),
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'control', 'volume'], $activity->tags()->list());
        $this->assertSame('Mounting volume: foo@/somewhere', $activity->toString());
    }
}
