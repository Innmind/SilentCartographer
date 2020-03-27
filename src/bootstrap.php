<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer;

use Innmind\OperatingSystem\OperatingSystem as OS;
use Innmind\Url\Url;
use Innmind\IPC\Process\Name;
use Innmind\CLI\Commands;
use function Innmind\IPC\bootstrap as ipc;

/**
 * @return array{protocol: Protocol, sub_routine: Name, http_server: callable(Url): OS, cli: callable(Url): OS, commands: callable(): Commands}
 */
function bootstrap(OS $os): array
{
    $protocol = new Protocol\Json;
    $subRoutine = new Name('silent_cartographer');
    $ipc = ipc($os);

    return [
        'protocol' => $protocol,
        'sub_routine' => $subRoutine,
        'http_server' => static function(Url $location) use ($os, $ipc, $protocol, $subRoutine): OS {
            return new OperatingSystem(
                $os,
                new SendActivity\DiscardSubsequentSend(
                    new SendActivity\IPC(
                        new Room($location),
                        Room\Program\Type::http(),
                        $os->process(),
                        $protocol,
                        $ipc,
                        $subRoutine
                    ),
                    $ipc,
                    $subRoutine
                )
            );
        },
        'cli' => static function(Url $location) use ($os, $ipc, $protocol, $subRoutine): OS {
            return new OperatingSystem(
                $os,
                new SendActivity\IPC(
                    new Room($location),
                    Room\Program\Type::cli(),
                    $os->process(),
                    $protocol,
                    $ipc,
                    $subRoutine
                )
            );
        },
        'commands' => static function() use ($os, $ipc, $protocol, $subRoutine): Commands {
            return new Commands(
                new Command\AutoStartSubRoutine(
                    new Command\Panel(
                        $ipc,
                        $subRoutine,
                        $protocol,
                        $os->process()->signals()
                    ),
                    $os->control()->processes()
                ),
                new Command\SubRoutine(
                    $ipc,
                    $subRoutine,
                    new SubRoutine(
                        $ipc->listen($subRoutine),
                        $protocol
                    )
                )
            );
        }
    ];
}
