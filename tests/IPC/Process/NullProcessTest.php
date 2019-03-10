<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\IPC\Process;

use Innmind\SilentCartographer\IPC\Process\NullProcess;
use Innmind\IPC\{
    Process,
    Process\Name,
    Message,
    Exception\Timedout,
};
use PHPUnit\Framework\TestCase;

class NullProcessTest extends TestCase
{
    public function testInterface()
    {
        $process = new NullProcess(
            $name = new Name('foo')
        );

        $this->assertInstanceOf(Process::class, $process);
        $this->assertSame($name, $process->name());
        $this->assertNull($process->send($this->createMock(Message::class)));

        try {
            $process->wait();
            $this->fail('it should throw');
        } catch (Timedout $e) {
            // pass
        }

        $this->assertFalse($process->closed());
        $this->assertNull($process->close());
        $this->assertTrue($process->closed());
    }
}
