<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Control;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Control\Reboot,
    Activity,
};
use PHPUnit\Framework\TestCase;

class RebootTest extends TestCase
{
    public function testInterface()
    {
        $activity = new Reboot;

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'control'], $activity->tags()->list());
        $this->assertSame('Reboot', $activity->toString());
    }
}
