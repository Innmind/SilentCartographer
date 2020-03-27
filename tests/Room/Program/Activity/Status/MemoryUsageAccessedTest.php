<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Status;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Status\MemoryUsageAccessed,
    Activity,
};
use Innmind\Server\Status\Server\{
    Memory,
    Memory\Bytes,
};
use PHPUnit\Framework\TestCase;

class MemoryUsageAccessedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new MemoryUsageAccessed(
            new Memory(
                new Bytes(1),
                new Bytes(2),
                new Bytes(3),
                new Bytes(4),
                new Bytes(5),
                new Bytes(6)
            )
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'status'], \iterator_to_array($activity->tags()));
        $this->assertSame(
            'Memory usage: total(1B) wired(2B) active(3B) free(4B) swap(5B) used(6B)',
            $activity->toString(),
        );
    }
}
