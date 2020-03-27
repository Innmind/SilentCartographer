<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Control;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Control\ExecutingCommand,
    Activity,
};
use Innmind\Server\Control\Server\Command;
use PHPUnit\Framework\TestCase;

class ExecutingCommandTest extends TestCase
{
    public function testInterface()
    {
        $activity = new ExecutingCommand(
            Command::foreground('php')
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'control', 'process'], $activity->tags()->list());
        $this->assertSame('Executing command: php', $activity->toString());
    }
}
