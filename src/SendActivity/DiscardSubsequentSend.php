<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\SendActivity;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity,
};
use Innmind\IPC\{
    IPC,
    Process\Name,
};

final class DiscardSubsequentSend implements SendActivity
{
    private $send;
    private $ipc;
    private $subRoutine;
    private $discard;

    public function __construct(
        SendActivity $send,
        IPC $ipc,
        Name $subRoutine
    ) {
        $this->send = $send;
        $this->ipc = $ipc;
        $this->subRoutine = $subRoutine;
    }

    public function __invoke(Activity $activity): void
    {
        if (\is_null($this->discard)) {
            $this->discard = !$this->ipc->exist($this->subRoutine);
        }

        if ($this->discard) {
            return;
        }

        ($this->send)($activity);
    }
}
