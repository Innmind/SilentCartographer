<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Remote\Ssh\Reboot,
    Activity,
};
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class RebootTest extends TestCase
{
    public function testInterface()
    {
        $activity = new Reboot(
            Url::of('ssh://user:pwd@example.com:22/')->authority(),
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'remote', 'ssh', 'control'], $activity->tags()->list());
        $this->assertSame('Reboot: user:pwd@example.com:22', $activity->toString());
    }
}
