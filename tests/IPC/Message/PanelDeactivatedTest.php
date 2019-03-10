<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\IPC\Message;

use Innmind\SilentCartographer\IPC\Message\PanelDeactivated;
use Innmind\IPC\Message;
use Innmind\Filesystem\MediaType\MediaType;
use Innmind\Immutable\Str;
use PHPUnit\Framework\TestCase;

class PanelDeactivatedTest extends TestCase
{
    public function testInterface()
    {
        $message = new PanelDeactivated;

        $this->assertInstanceOf(Message::class, $message);
        $this->assertSame('text/plain', (string) $message->mediaType());
        $this->assertSame('panel_deactivated', (string) $message->content());
    }

    public function testEquals()
    {
        $message = new PanelDeactivated;

        $this->assertFalse($message->equals(new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('')
        )));
        $this->assertFalse($message->equals(new Message\Generic(
            new MediaType('text', 'plain'),
            Str::of('')
        )));
        $this->assertTrue($message->equals(new Message\Generic(
            new MediaType('text', 'plain'),
            Str::of('panel_deactivated')
        )));
    }
}
