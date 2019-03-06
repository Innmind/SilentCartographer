<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer;

use Innmind\OperatingSystem\OperatingSystem as OS;
use Innmind\Url\UrlInterface;
use Innmind\IPC\Process\Name;
use function Innmind\IPC\bootstrap as ipc;

function bootstrap(): array
{
    $protocol = new Protocol\Json;
    $subRoutine = new Name('silent_cartographer');

    return [
        'protocol' => $protocol,
        'sub_routine' => $subRoutine,
        'http_server' => static function(OS $os, UrlInterface $location) use ($protocol, $subRoutine): OS {
            $ipc = ipc($os);

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
        'cli' => static function(OS $os, UrlInterface $location) use ($protocol, $subRoutine): OS {
            return new OperatingSystem(
                $os,
                new SendActivity\IPC(
                    new Room($location),
                    Room\Program\Type::cli(),
                    $os->process(),
                    $protocol,
                    ipc($os),
                    $subRoutine
                )
            );
        },
    ];
}
