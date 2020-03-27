<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program;

final class Type
{
    private static ?self $cli = null;
    private static ?self $http = null;

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function cli(): self
    {
        return self::$cli ??= new self('cli');
    }

    public static function http(): self
    {
        return self::$http ??= new self('http');
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
