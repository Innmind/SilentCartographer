<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program;

use Innmind\SilentCartographer\Room\Program\Type;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Type::class, Type::cli());
        $this->assertInstanceOf(Type::class, Type::http());
        $this->assertSame(Type::cli(), Type::cli());
        $this->assertSame(Type::http(), Type::http());
        $this->assertSame('cli', (string) Type::cli());
        $this->assertSame('http', (string) Type::http());
    }
}
