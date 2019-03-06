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

final class SubRoutine implements Command
{
    private $listen;

    public function __construct(Listen $listen)
    {
        $this->listen = $listen;
    }

    public function __invoke(Environment $env, Arguments $arguments, Options $options): void
    {
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
