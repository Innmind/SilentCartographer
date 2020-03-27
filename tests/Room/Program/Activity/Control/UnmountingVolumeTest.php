<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Control;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Control\UnmountingVolume,
    Activity,
};
use Innmind\Server\Control\Server\Volumes\Name;
use PHPUnit\Framework\TestCase;

class UnmountingVolumeTest extends TestCase
{
    public function testInterface()
    {
        $activity = new UnmountingVolume(
            new Name('foo'),
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'control', 'volume'], $activity->tags()->list());
        $this->assertSame('Unmounting volume: foo', $activity->toString());
    }
}
