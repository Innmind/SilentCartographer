<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Remote\Ssh\ProcessKilled,
    Activity,
};
use Innmind\Server\Control\Server\Process\Pid;
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class ProcessKilledTest extends TestCase
{
    public function testInterface()
    {
        $activity = new ProcessKilled(
            Url::of('ssh://foo@bar:2224/')->authority(),
            new Pid(42)
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'remote', 'ssh', 'control', 'process'], \iterator_to_array($activity->tags()));
        $this->assertSame('Process killed: [foo@bar:2224] 42', (string) $activity);
    }
}
