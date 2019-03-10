<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Control;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Control\ProcessKilled,
    Activity,
};
use Innmind\Server\Control\Server\Process\Pid;
use PHPUnit\Framework\TestCase;

class ProcessKilledTest extends TestCase
{
    public function testInterface()
    {
        $activity = new ProcessKilled(
            new Pid(42)
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'control', 'process'], \iterator_to_array($activity->tags()));
        $this->assertSame('Process killed: 42', (string) $activity);
    }
}
