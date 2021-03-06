<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\IPC\Process;

use Innmind\IPC\{
    Process,
    Message,
    Exception\Timedout,
};
use Innmind\TimeContinuum\ElapsedPeriod;

final class NullProcess implements Process
{
    private Process\Name $name;
    private bool $closed = false;

    public function __construct(Process\Name $name)
    {
        $this->name = $name;
    }

    public function name(): Process\Name
    {
        return $this->name;
    }

    public function send(Message ...$messages): void
    {
        // pass
    }

    public function wait(ElapsedPeriod $timeout = null): Message
    {
        throw new Timedout;
    }

    public function close(): void
    {
        $this->closed = true;
    }

    public function closed(): bool
    {
        return $this->closed;
    }
}
