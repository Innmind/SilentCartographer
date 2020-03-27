<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Command;

use Innmind\SilentCartographer\Command\AutoStartSubRoutine;
use Innmind\CLI\{
    Command,
    Command\Arguments,
    Command\Options,
    Environment,
};
use Innmind\Server\Control\Server\Processes;
use Innmind\Url\Path;
use PHPUnit\Framework\TestCase;

class AutoStartSubRoutineTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Command::class,
            new AutoStartSubRoutine(
                $this->createMock(Command::class),
                $this->createMock(Processes::class)
            )
        );
    }

    public function testUsage()
    {
        $command = new AutoStartSubRoutine(
            $inner = $this->createMock(Command::class),
            $this->createMock(Processes::class)
        );
        $inner
            ->expects($this->once())
            ->method('toString')
            ->willReturn('foo');

        $this->assertSame('foo', $command->toString());
    }

    public function testInvokation()
    {
        $command = new AutoStartSubRoutine(
            $inner = $this->createMock(Command::class),
            $processes = $this->createMock(Processes::class)
        );
        $processes
            ->expects($this->once())
            ->method('execute')
            ->with($this->callback(static function($command): bool {
                return $command->toString() === "silent-cartographer 'sub-routine'" &&
                    $command->workingDirectory()->toString() === '/somewhere';
            }));
        $env = $this->createMock(Environment::class);
        $arguments = new Arguments;
        $options = new Options;
        $env
            ->expects($this->once())
            ->method('workingDirectory')
            ->willReturn(Path::of('/somewhere'));
        $inner
            ->expects($this->once())
            ->method('__invoke')
            ->with($env, $arguments, $options);

        $this->assertNull($command($env, $arguments, $options));
    }
}
