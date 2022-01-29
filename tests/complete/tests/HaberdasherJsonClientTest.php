<?php

declare(strict_types=1);

namespace Tests\Twirp\Complete;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Client\ClientInterface;
use Twirp\Tests\Complete\Proto\HaberdasherJsonClient;
use Twirp\Tests\Complete\Proto\Size;

final class HaberdasherJsonClientTest extends \PHPUnit\Framework\TestCase
{
    use ProphecyTrait;

    public function testItMakesRequestWithJsonSerialization(): void
    {
        // Constructs a json client with mocked http.
        $http = $this->prophesize(ClientInterface::class);
        $client = new HaberdasherJsonClient('www.example.com', $http->reveal());

        // Sets http req expectations asserting that correct request body and header is present.
        $isExpectedReq = function (Request $req): bool {
            return 'application/json' === $req->getHeaderLine('content-type') &&
                '{"inches":123}' === (string) $req->getBody();
        };
        $res = new Response(200, [], '{"size":123,"color":"golden","name":"crown"}');
        $http->sendRequest(Argument::that($isExpectedReq))->willReturn($res);

        // Makes a request and asserts response.
        $hat = $client->MakeHat([], (new Size())->setInches(123));
        $this->assertSame(123, $hat->getSize());
        $this->assertSame('golden', $hat->getColor());
        $this->assertSame('crown', $hat->getName());
    }
}
