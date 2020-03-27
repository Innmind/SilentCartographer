<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Remote\Ssh\Shutdown,
    Activity,
};
use Innmind\Server\Control\Server\Volumes\Name;
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class ShutdownTest extends TestCase
{
    public function testInterface()
    {
        $activity = new Shutdown(
            Url::of('ssh://user:pwd@example.com:22/')->authority(),
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'remote', 'ssh', 'control'], $activity->tags()->list());
        $this->assertSame('Shutdown: user:pwd@example.com:22', $activity->toString());
    }
}
