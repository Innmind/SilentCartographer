<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\IPC\Message;

use Innmind\IPC\Message;
use Innmind\Filesystem\MediaType;
use Innmind\Immutable\Str;

final class PanelDeactivated implements Message
{
    private $content;
    private $mediaType;

    public function __construct()
    {
        $this->content = Str::of('panel_deactivated');
        $this->mediaType = new MediaType\MediaType('text', 'plain');
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
        return (string) $this->mediaType === (string) $message->mediaType() &&
            (string) $this->content === (string) $message->content();
    }
}
