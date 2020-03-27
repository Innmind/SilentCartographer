<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\CurrentProcess;

use Innmind\SilentCartographer\Room\Program\{
    Activity\CurrentProcess\CurrentMemory,
    Activity,
};
use Innmind\Server\Status\Server\Memory\Bytes;
use PHPUnit\Framework\TestCase;

class CurrentMemoryTest extends TestCase
{
    public function testInterface()
    {
        $activity = new CurrentMemory(
            new Bytes(42)
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'process'], $activity->tags()->list());
        $this->assertSame('Process memory: 42B', $activity->toString());
    }
}
