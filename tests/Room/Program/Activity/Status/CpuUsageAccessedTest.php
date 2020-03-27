<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Status;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Status\CpuUsageAccessed,
    Activity,
};
use Innmind\Server\Status\Server\{
    Cpu,
    Cpu\Percentage,
    Cpu\Cores,
};
use PHPUnit\Framework\TestCase;

class CpuUsageAccessedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new CpuUsageAccessed(
            $cpu = new Cpu(
                new Percentage(10),
                new Percentage(20),
                new Percentage(30),
                new Cores(1)
            )
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'status'], $activity->tags()->list());
        $this->assertSame($cpu->toString(), $activity->toString());
    }
}
