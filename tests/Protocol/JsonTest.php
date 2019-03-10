<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Protocol;

use Innmind\SilentCartographer\{
    Protocol\Json,
    Protocol,
    RoomActivity,
    Room,
    Room\Program,
    Room\Program\Type,
    Room\Program\Activity,
    Room\Program\Activity\Tags,
    Exception\UnknownProtocol,
};
use Innmind\Server\Status\Server\Process\Pid;
use Innmind\Url\Url;
use Innmind\IPC\Message;
use Innmind\Filesystem\MediaType\MediaType;
use Innmind\Immutable\Str;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Protocol::class, new Json);
    }

    public function testEncode()
    {
        $protocol = new Json;
        $activity = new RoomActivity(
            new Program(
                new Pid(42),
                Type::cli(),
                new Room(
                    Url::fromString('file:///somewhere/on/the/filesystem')
                )
            ),
            new Activity\Generic(
                new Tags('foo', 'bar', 'baz'),
                'watev'
            )
        );

        $message = $protocol->encode($activity);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertSame('application/json', (string) $message->mediaType());
        $this->assertSame(
            '{"room":{"location":"file:\/\/\/somewhere\/on\/the\/filesystem","program":{"id":42,"type":"cli"},"activity":{"tags":["foo","bar","baz"],"message":"watev"}}}',
            (string) $message->content()
        );
    }

    public function testThrowWhenUnknownProtocol()
    {
        $protocol = new Json;

        $this->expectException(UnknownProtocol::class);

        $protocol->decode(new Message\Generic(
            new MediaType('text', 'plain'),
            Str::of('{"room":{"location":"file:\/\/\/somewhere\/on\/the\/filesystem","program":{"id":42,"type":"cli"},"activity":{"tags":["foo","bar","baz"],"message":"watev"}}}')
        ));
    }

    public function testDecode()
    {
        $protocol = new Json;

        $roomActivity = $protocol->decode(new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{"room":{"location":"file:\/\/\/somewhere\/on\/the\/filesystem","program":{"id":42,"type":"cli"},"activity":{"tags":["foo","bar","baz"],"message":"watev"}}}')
        ));

        $this->assertInstanceOf(RoomActivity::class, $roomActivity);
        $this->assertSame('file:///somewhere/on/the/filesystem', (string) $roomActivity->program()->room()->location());
        $this->assertSame(42, $roomActivity->program()->id()->toInt());
        $this->assertSame(Type::cli(), $roomActivity->program()->type());
        $this->assertSame(['foo', 'bar', 'baz'], \iterator_to_array($roomActivity->activity()->tags()));
        $this->assertSame('watev', (string) $roomActivity->activity());
    }
}
