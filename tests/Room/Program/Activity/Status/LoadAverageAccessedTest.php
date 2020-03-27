<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Status;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Status\LoadAverageAccessed,
    Activity,
};
use Innmind\Server\Status\Server\LoadAverage;
use PHPUnit\Framework\TestCase;

class LoadAverageAccessedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new LoadAverageAccessed(
            new LoadAverage(1.1, 5.1, 15.1)
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'status'], \iterator_to_array($activity->tags()));
        $this->assertSame('Load average: 1.1, 5.1, 15.1', $activity->toString());
    }
}
