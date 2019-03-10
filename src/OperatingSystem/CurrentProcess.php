<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\CurrentProcess\ProcessForked,
    Room\Program\Activity\CurrentProcess\ProcessHalted,
};
use Innmind\OperatingSystem\{
    CurrentProcess as CurrentProcessInterface,
    CurrentProcess\ForkSide,
    CurrentProcess\Children,
    CurrentProcess\Signals,
};
use Innmind\Server\Status\Server\Process\Pid;
use Innmind\TimeContinuum\PeriodInterface;

final class CurrentProcess implements CurrentProcessInterface
{
    private $process;
    private $send;

    public function __construct(CurrentProcessInterface $process, SendActivity $send)
    {
        $this->process = $process;
        $this->send = $send;
    }

    public function id(): Pid
    {
        return $this->process->id();
    }

    /**
     * {@inheritdoc}
     */
    public function fork(): ForkSide
    {
        $side = $this->process->fork();

        if ($side->parent()) {
            ($this->send)(new ProcessForked($side->child()));
        }

        return $side;
    }

    public function children(): Children
    {
        return $this->process->children();
    }

    public function signals(): Signals
    {
        return $this->process->signals();
    }

    public function halt(PeriodInterface $period): void
    {
        ($this->send)(new ProcessHalted($period));

        $this->process->halt($period);
    }
}
