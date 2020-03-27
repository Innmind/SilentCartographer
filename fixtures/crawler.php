<?php
declare(strict_types = 1);

require __DIR__.'/../vendor/autoload.php';

use Innmind\OperatingSystem\Factory;
use Innmind\Url\Url;
use Innmind\Http\{
    Message\Request\Request,
    Message\Method,
    ProtocolVersion,
};
use Innmind\TimeContinuum\Earth\Period\Millisecond;
use function Innmind\SilentCartographer\bootstrap;

$os = Factory::build();
$os = bootstrap($os)['cli'](Url::of('file://'.__DIR__));

do {
    $os->remote()->http()(new Request(
        Url::of('https://github.com'),
        Method::get(),
        new ProtocolVersion(2, 0)
    ));
    $os->process()->halt(new Millisecond(200));
} while (true);
