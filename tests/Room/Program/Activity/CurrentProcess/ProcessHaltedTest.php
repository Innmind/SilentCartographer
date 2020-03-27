<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\CurrentProcess;

use Innmind\SilentCartographer\Room\Program\{
    Activity\CurrentProcess\ProcessHalted,
    Activity,
};
use Innmind\TimeContinuum\Earth\Period\Minute;
use PHPUnit\Framework\TestCase;

class ProcessHaltedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new ProcessHalted(
            new Minute(42)
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'process'], $activity->tags()->list());
        $this->assertSame('Process halted: 2520000ms', $activity->toString());
    }
}
