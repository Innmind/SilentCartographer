<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Status;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Status\ProcessStatusAccessed,
    Activity,
};
use Innmind\Server\Status\Server\Process\Pid;
use PHPUnit\Framework\TestCase;

class ProcessStatusAccessedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new ProcessStatusAccessed(
            new Pid(42)
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'status'], \iterator_to_array($activity->tags()));
        $this->assertSame('Process status accessed: 42', (string) $activity);
    }
}
