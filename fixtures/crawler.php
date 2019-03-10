<?php
declare(strict_types = 1);

require __DIR__.'/../vendor/autoload.php';

use Innmind\OperatingSystem\Factory;
use Innmind\Url\Url;
use Innmind\Http\{
    Message\Request\Request,
    Message\Method\Method,
    ProtocolVersion\ProtocolVersion,
};
use Innmind\TimeContinuum\Period\Earth\Millisecond;
use function Innmind\SilentCartographer\bootstrap;

$os = Factory::build();
$os = bootstrap($os)['cli'](Url::fromString('file://'.__DIR__));

do {
    $os->remote()->http()(new Request(
        Url::fromString('http://example.com'),
        Method::get(),
        new ProtocolVersion(2, 0)
    ));
    $os->process()->halt(new Millisecond(200));
} while (true);
