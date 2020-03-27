<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Status;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Status\VolumeUsageAccessed,
    Activity,
};
use Innmind\Server\Status\Server\Disk\Volume\MountPoint;
use PHPUnit\Framework\TestCase;

class VolumeUsageAccessedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new VolumeUsageAccessed(
            new MountPoint('/dev')
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'status'], $activity->tags()->list());
        $this->assertSame('Volume usage accessed: /dev', $activity->toString());
    }
}
