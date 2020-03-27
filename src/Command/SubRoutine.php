<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Command;

use Innmind\SilentCartographer\SubRoutine as Listen;
use Innmind\CLI\{
    Command,
    Command\Arguments,
    Command\Options,
    Environment,
};
use Innmind\IPC\{
    IPC,
    Process\Name,
};

final class SubRoutine implements Command
{
    private IPC $ipc;
    private Name $subRoutine;
    private Listen $listen;

    public function __construct(
        IPC $ipc,
        Name $subRoutine,
        Listen $listen
    ) {
        $this->ipc = $ipc;
        $this->subRoutine = $subRoutine;
        $this->listen = $listen;
    }

    public function __invoke(Environment $env, Arguments $arguments, Options $options): void
    {
        if ($this->ipc->exist($this->subRoutine)) {
            return;
        }

        ($this->listen)();
    }

    public function __toString(): string
    {
        return <<<USAGE
sub-routine

Start the server that collects all activity messages and forward them to panels
USAGE;
    }
}
