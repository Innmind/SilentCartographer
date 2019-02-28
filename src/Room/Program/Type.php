<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program;

final class Type
{
    private static $cli;
    private static $http;

    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function cli(): self
    {
        return self::$cli ?? self::$cli = new self('cli');
    }

    public static function http(): self
    {
        return self::$http ?? self::$http = new self('http');
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
