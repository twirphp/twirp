<?php

require __DIR__.'/../lib/vendor/autoload.php';

$client = new \Twitch\Twirp\Example\HaberdasherClient($argv[1]);

$size = new \Twitch\Twirp\Example\Size();
$size->setInches(1234);

try {
    $hat = $client->MakeHat([], $size);

    var_dump($hat->serializeToJsonString());
} catch (\Twirp\Error $e) {
    var_dump(json_encode([
        'code' => $e->getErrorCode(),
        'msg' => $e->getMessage(),
        'meta' => $e->getMetaMap(),
    ]));
}
