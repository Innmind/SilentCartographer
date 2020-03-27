<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Ports;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Ports\PortOpened,
    Activity,
};
use Innmind\Socket\Internet\Transport;
use Innmind\IP\IPv4;
use Innmind\Url\Authority\Port;
use PHPUnit\Framework\TestCase;

class PortOpenedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new PortOpened(
            Transport::tcp(),
            IPv4::localhost(),
            Port::of(80)
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'socket', 'port'], $activity->tags()->list());
        $this->assertSame('Port opened: tcp://127.0.0.1:80', $activity->toString());
    }
}
