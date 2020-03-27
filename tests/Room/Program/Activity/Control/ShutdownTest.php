<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Control;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Control\Shutdown,
    Activity,
};
use Innmind\Server\Control\Server\Volumes\Name;
use PHPUnit\Framework\TestCase;

class ShutdownTest extends TestCase
{
    public function testInterface()
    {
        $activity = new Shutdown;

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'control'], $activity->tags()->list());
        $this->assertSame('Shutdown', $activity->toString());
    }
}
