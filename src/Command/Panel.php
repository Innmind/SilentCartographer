<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Command;

use Innmind\SilentCartographer\{
    Protocol,
    IPC\Message\PanelActivated,
    IPC\Message\PanelDeactivated,
};
use Innmind\IPC\{
    IPC,
    Process,
    Process\Name,
    Exception\RuntimeException,
};
use Innmind\OperatingSystem\CurrentProcess\Signals;
use Innmind\Signals\Signal;
use Innmind\CLI\{
    Command,
    Command\Arguments,
    Command\Options,
    Environment,
};
use Innmind\Stream\Writable;
use Innmind\Immutable\Str;
use function Innmind\Immutable\unwrap;

final class Panel implements Command
{
    private IPC $ipc;
    private Name $subRoutine;
    private Protocol $protocol;
    private Signals $signals;

    public function __construct(
        IPC $ipc,
        Name $subRoutine,
        Protocol $protocol,
        Signals $signals
    ) {
        $this->ipc = $ipc;
        $this->subRoutine = $subRoutine;
        $this->protocol = $protocol;
        $this->signals = $signals;
    }

    public function __invoke(Environment $env, Arguments $arguments, Options $options): void
    {
        $this->ipc->wait($this->subRoutine);
        $process = $this->ipc->get($this->subRoutine);
        $this->safe($process);
        $process->send(new PanelActivated(...unwrap($arguments->pack())));

        $this->print(
            $process,
            $env->output(),
            $options->contains('format') ? $options->get('format') : '[{type}][{pid}][{room}][{tags}] {activity}'
        );
    }

    public function toString(): string
    {
        return <<<USAGE
panel ...tags --format=

Open a panel to display all activity that matches the given tags

When no tag provided it will display all messages
Available placeholders for the format option:
* {type}
* {pid}
* {room}
* {tags}
* {activity}
USAGE;
    }

    private function safe(Process $process): void
    {
        $softClose = function() use ($process): void {
            $process->send(new PanelDeactivated);

            try {
                $process->close();
            } catch (RuntimeException $e) {
                // it can happen if the sub routine closes before we trigger the
                // close
            }
        };

        $this->signals->listen(Signal::hangup(), $softClose);
        $this->signals->listen(Signal::interrupt(), $softClose);
        $this->signals->listen(Signal::abort(), $softClose);
        $this->signals->listen(Signal::terminate(), $softClose);
        $this->signals->listen(Signal::terminalStop(), $softClose);
        $this->signals->listen(Signal::alarm(), $softClose);
    }

    private function print(Process $process, Writable $output, string $format): void
    {
        try {
            do {
                $message = $process->wait();
                $roomActivity = $this->protocol->decode($message);

                $output->write(
                    Str::of("$format\n")
                        ->replace('{type}', $roomActivity->program()->type()->toString())
                        ->replace('{pid}', $roomActivity->program()->id()->toString())
                        ->replace('{room}', $roomActivity->program()->room()->location()->path()->toString())
                        ->replace('{tags}', \implode('/', $roomActivity->activity()->tags()->list()))
                        ->replace('{activity}', $roomActivity->activity()->toString()),
                );
            } while (!$process->closed());
        } catch (RuntimeException $e) {
            // stop the loop
        }
    }
}
