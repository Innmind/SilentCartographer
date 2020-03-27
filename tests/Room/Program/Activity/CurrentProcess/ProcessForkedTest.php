<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\CurrentProcess;

use Innmind\SilentCartographer\Room\Program\{
    Activity\CurrentProcess\ProcessForked,
    Activity,
};
use Innmind\Server\Control\Server\Process\Pid;
use PHPUnit\Framework\TestCase;

class ProcessForkedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new ProcessForked(
            new Pid(42)
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'process'], \iterator_to_array($activity->tags()));
        $this->assertSame('Process forked: 42', $activity->toString());
    }
}
