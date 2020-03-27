<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\IPC\Message;

use Innmind\IPC\Message;
use Innmind\MediaType\MediaType;
use Innmind\Immutable\Str;

final class PanelDeactivated implements Message
{
    private Str $content;
    private MediaType $mediaType;

    public function __construct()
    {
        $this->content = Str::of('panel_deactivated');
        $this->mediaType = new MediaType('text', 'plain');
    }

    public function mediaType(): MediaType
    {
        return $this->mediaType;
    }

    public function content(): Str
    {
        return $this->content;
    }

    public function equals(Message $message): bool
    {
        return $this->mediaType->toString() === $message->mediaType()->toString() &&
            $this->content->toString() === $message->content()->toString();
    }
}
