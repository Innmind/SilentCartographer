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
    Exception\ConnectionClosed,
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

final class Panel implements Command
{
    private $ipc;
    private $subRoutine;
    private $protocol;
    private $signals;

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
        $process->send(new PanelActivated(...$arguments->pack()));

        $this->print($process, $env->output());
    }

    public function __toString(): string
    {
        return <<<USAGE
panel ...tags

Open a panel to display all activity that matches the given tags

When no tag provided it will display all messages
USAGE;
    }

    private function safe(Process $process): void
    {
        $softClose = function() use ($process): void {
            $process->send(new PanelDeactivated);
            $process->close();
        };

        $this->signals->listen(Signal::hangup(), $softClose);
        $this->signals->listen(Signal::interrupt(), $softClose);
        $this->signals->listen(Signal::abort(), $softClose);
        $this->signals->listen(Signal::terminate(), $softClose);
        $this->signals->listen(Signal::terminalStop(), $softClose);
        $this->signals->listen(Signal::alarm(), $softClose);
    }

    private function print(Process $process, Writable $output): void
    {
        try {
            do {
                $message = $process->wait();
                $roomActivity = $this->protocol->decode($message);

                $output->write(Str::of("[%s][%s][%s][%s] %s\n")->sprintf(
                    $roomActivity->program()->type(),
                    $roomActivity->program()->id(),
                    $roomActivity->program()->room()->location()->path(),
                    \implode('/', \iterator_to_array($roomActivity->activity()->tags())),
                    $roomActivity->activity()
                ));
            } while (!$process->closed());
        } catch (ConnectionClosed $e) {
            // stop the loop
        }
    }
}
