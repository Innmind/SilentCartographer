<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program;

use Innmind\SilentCartographer\Exception\LogicException;

final class Type
{
    private static ?self $cli = null;
    private static ?self $http = null;

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function of(string $type): self
    {
        switch ($type) {
            case 'cli':
                return self::cli();

            case 'http':
                return self::http();
        }

        throw new LogicException("Unknown type '$type'");
    }

    public static function cli(): self
    {
        return self::$cli ??= new self('cli');
    }

    public static function http(): self
    {
        return self::$http ??= new self('http');
    }

    public function toString(): string
    {
        return $this->value;
    }
}
