<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Remote\Ssh\ExecutingCommand,
    Activity,
};
use Innmind\Server\Control\Server\Command;
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class ExecutingCommandTest extends TestCase
{
    public function testInterface()
    {
        $activity = new ExecutingCommand(
            Url::of('ssh://foo@bar:2224/')->authority(),
            Command::foreground('php')
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'remote', 'ssh', 'control', 'process'], $activity->tags()->list());
        $this->assertSame('Executing command: [foo@bar:2224] php', $activity->toString());
    }
}
