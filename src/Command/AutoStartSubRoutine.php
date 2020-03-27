<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Command;

use Innmind\CLI\{
    Command,
    Command\Arguments,
    Command\Options,
    Environment,
};
use Innmind\Server\Control\Server\{
    Processes,
    Command as Executable,
};

final class AutoStartSubRoutine implements Command
{
    private Command $run;
    private Processes $processes;

    public function __construct(Command $run, Processes $processes)
    {
        $this->run = $run;
        $this->processes = $processes;
    }

    public function __invoke(Environment $env, Arguments $arguments, Options $options): void
    {
        $this
            ->processes
            ->execute(
                Executable::background('silent-cartographer')
                    ->withArgument('sub-routine')
                    ->withWorkingDirectory((string) $env->workingDirectory())
            );

        ($this->run)($env, $arguments, $options);
    }

    public function __toString(): string
    {
        return (string) $this->run;
    }
}
