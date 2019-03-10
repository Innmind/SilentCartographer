<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\SendActivity;

use Innmind\SilentCartographer\{
    SendActivity,
    Room,
    Room\Program,
    Room\Program\Activity,
    Room\Program\Type,
    Protocol,
    RoomActivity,
    IPC\Process\NullProcess,
};
use Innmind\OperatingSystem\CurrentProcess;
use Innmind\IPC\{
    IPC as IPCInterface,
    Process,
    Process\Name,
    Exception\MessageNotSent,
    Exception\FailedToConnect,
};

final class IPC implements SendActivity
{
    private $room;
    private $type;
    private $process;
    private $protocol;
    private $ipc;
    private $subRoutine;
    private $client;

    public function __construct(
        Room $room,
        Type $type,
        CurrentProcess $process,
        Protocol $protocol,
        IPCInterface $ipc,
        Name $subRoutine
    ) {
        $this->room = $room;
        $this->type = $type;
        $this->process = $process;
        $this->protocol = $protocol;
        $this->ipc = $ipc;
        $this->subRoutine = $subRoutine;
    }

    public function __invoke(Activity $activity): void
    {
        // we access the pid here instead of at construct time to allow to always
        // have the correct pid in case of a fork of the process
        try {
            $this->client()->send(
                $this->protocol->encode(new RoomActivity(
                    new Program(
                        $this->process->id(),
                        $this->type,
                        $this->room
                    ),
                    $activity
                ))
            );
        } catch (MessageNotSent $e) {
            // nothing to do
        }
    }

    private function client(): Process
    {
        if (!$this->ipc->exist($this->subRoutine)) {
            return new NullProcess($this->subRoutine);
        }

        if ($this->client instanceof Process && $this->client->closed()) {
            $this->client = null;

            return $this->client();
        }

        try {
            return $this->client ?? $this->client = $this->ipc->get($this->subRoutine);
        } catch (FailedToConnect $e) {
            return new NullProcess($this->subRoutine);
        }
    }
}
