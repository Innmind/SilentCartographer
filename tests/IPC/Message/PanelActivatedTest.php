<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\IPC\Message;

use Innmind\SilentCartographer\IPC\Message\PanelActivated;
use Innmind\IPC\Message;
use Innmind\Filesystem\MediaType\MediaType;
use Innmind\Immutable\Str;
use PHPUnit\Framework\TestCase;

class PanelActivatedTest extends TestCase
{
    public function testInterface()
    {
        $message = new PanelActivated('foo', 'bar', 'baz');

        $this->assertInstanceOf(Message::class, $message);
        $this->assertSame('application/json', (string) $message->mediaType());
        $this->assertSame(
            '{"message":"panel_activated","tags":["foo","bar","baz"]}',
            (string) $message->content()
        );
    }

    public function testNotEqualsWhenDifferentMediaType()
    {
        $message = new PanelActivated('foo', 'bar', 'baz');

        $this->assertFalse($message->equals(new Message\Generic(
            new MediaType('text', 'plain'),
            Str::of('')
        )));
    }

    public function testNotEqualsWhenNoMessage()
    {
        $message = new PanelActivated('foo', 'bar', 'baz');

        $this->assertFalse($message->equals(new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{}')
        )));
    }

    public function testNotEqualsWhenNotRightMessage()
    {
        $message = new PanelActivated('foo', 'bar', 'baz');

        $this->assertFalse($message->equals(new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{"message":"foo"}')
        )));
    }

    public function testEquals()
    {
        $message = new PanelActivated('watev');

        $this->assertTrue($message->equals(new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{"message":"panel_activated"}')
        )));
        $this->assertTrue($message->equals(new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{"message":"panel_activated","tags":["foo"]}')
        )));
    }
}
