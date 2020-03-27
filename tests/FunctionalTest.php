<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer;

use Innmind\OperatingSystem\Factory;
use Innmind\Server\Control\Server\{
    Command,
    Signal,
};
use Innmind\TimeContinuum\Earth\Period\Second;
use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    public function testBehaviour()
    {
        if (getenv('CI') && PHP_OS === 'Linux') {
            return;
        }

        $os = Factory::build();
        $processes = $os->control()->processes();
        $crawler = $processes->execute(
            Command::foreground('php')
                ->withArgument('fixtures/crawler.php')
        );
        // sub routine started manually as the auto start can only work in global
        // state as locally it can find the `silent-cartographer` executable
        $subRoutine = $processes->execute(
            Command::foreground('./silent-cartographer')
                ->withArgument('sub-routine')
        );
        $panel = $processes->execute(
            Command::foreground('./silent-cartographer')
                ->withArgument('panel')
        );

        $os->process()->halt(new Second(1));
        $processes->kill($subRoutine->pid(), Signal::interrupt());
        $processes->kill($panel->pid(), Signal::interrupt());
        $processes->kill($crawler->pid(), Signal::interrupt());
        $pid = $crawler->pid();

        $dir = dirname(__DIR__);
        $this->assertStringContainsString(
            "[cli][{$pid->toString()}][$dir/fixtures][os/remote/http] Request sent: GET https://github.com/ HTTP/2.0",
            $panel->output()->toString(),
        );
        $this->assertStringContainsString(
            "[cli][{$pid->toString()}][$dir/fixtures][os/remote/http] Response received: HTTP/1.1 200 OK",
            $panel->output()->toString(),
        );
        $this->assertStringContainsString(
            "[cli][{$pid->toString()}][$dir/fixtures][os/process] Process halted: 200ms",
            $panel->output()->toString(),
        );
    }
}
