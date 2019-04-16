<?php

require __DIR__.'/../vendor/autoload.php';

$client = new \Twitch\Twirp\Example\HaberdasherClient($argv[1]);

$size = new \Twitch\Twirp\Example\Size();
$size->setInches(1234);

try {
    $hat = $client->MakeHat([], $size);

    var_dump($hat->serializeToJsonString());
} catch (\Twirp\Error $e) {
    var_dump(json_encode([
        'code' => $e->code(),
        'msg' => $e->msg(),
        'meta' => $e->metaMap(),
    ]));
}
