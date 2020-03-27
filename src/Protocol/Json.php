<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Protocol;

use Innmind\SilentCartographer\{
    Protocol,
    RoomActivity,
    Room,
    Room\Program,
    Room\Program\Type,
    Room\Program\Activity,
    Room\Program\Activity\Tags,
    Exception\UnknownProtocol,
};
use Innmind\IPC\Message;
use Innmind\MediaType\MediaType;
use Innmind\Url\Url;
use Innmind\Server\Control\Server\Process\Pid;
use Innmind\Json\Json as Format;
use Innmind\Immutable\Str;

final class Json implements Protocol
{
    public function encode(RoomActivity $roomActivity): Message
    {
        return new Message\Generic(
            new MediaType('application', 'json'),
            Str::of(Format::encode([
                'room' => [
                    'location' => $roomActivity->program()->room()->location()->toString(),
                    'program' => [
                        'id' => $roomActivity->program()->id()->toInt(),
                        'type' => (string) $roomActivity->program()->type(),
                    ],
                    'activity' => [
                        'tags' => \iterator_to_array($roomActivity->activity()->tags()),
                        'message' => (string) $roomActivity->activity(),
                    ]
                ],
            ]))
        );
    }

    public function decode(Message $message): RoomActivity
    {
        if ($message->mediaType()->toString() !== 'application/json') {
            throw new UnknownProtocol;
        }

        /** @var array{room: array{program: array{id: int, type: 'cli'|'http'}, location: string, activity: array{message: string, tags: list<string>}}} */
        $data = Format::decode($message->content()->toString());

        return new RoomActivity(
            new Program(
                new Pid($data['room']['program']['id']),
                Type::of($data['room']['program']['type']),
                new Room(
                    Url::of($data['room']['location'])
                )
            ),
            new Activity\Generic(
                new Tags(...$data['room']['activity']['tags']),
                $data['room']['activity']['message']
            )
        );
    }
}
