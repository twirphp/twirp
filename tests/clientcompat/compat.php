#!/usr/bin/env php
<?php

require_once __DIR__ . '/../../lib/vendor/autoload.php';

use Google\Protobuf\Internal\GPBDecodeException;
use Twirp\Clientcompat\ClientCompatMessage;
use Twirp\Clientcompat\ClientCompatMessage_CompatServiceMethod;
use Twirp\Clientcompat\CompatServiceClient;

$message = new ClientCompatMessage();

try {
    $message->mergeFromString(file_get_contents('php://stdin'));
} catch (GPBDecodeException $e) {
    file_put_contents('php://stderr', 'unmarshal err: ' . $e->getMessage());
    exit(1);
}

$client = new CompatServiceClient($message->getServiceAddress());

switch ($message->getMethod()) {
    case ClientCompatMessage_CompatServiceMethod::NOOP:
        try {
            doNoop($client, $message->getRequest());
        } catch (\Throwable $e) {
            file_put_contents('php://stderr', 'doNoop err:' . $e->getMessage());
        }
        break;

    case ClientCompatMessage_CompatServiceMethod::METHOD:
        try {
            doMethod($client, $message->getRequest());
        } catch (\Throwable $e) {
            file_put_contents('php://stderr', 'doMethod err:' . $e->getMessage());
        }
        break;

    default:
        file_put_contents('php://stderr', 'unexpected method: ' . $message->getMethod());
        exit(1);
        break;
}

function doNoop(CompatServiceClient $client, $in)
{
    $req = new \Twirp\Clientcompat\PBEmpty();
    $req->mergeFromString($in);

    try {
        $resp = $client->noopMethod([], $req);

        echo $resp->serializeToString();
    } catch (\Twirp\Error $e) {
        file_put_contents('php://stderr', $e->getErrorCode());
    }
}

function doMethod(CompatServiceClient $client, $in)
{
    $req = new \Twirp\Clientcompat\Req();
    $req->mergeFromString($in);

    try {
        $resp = $client->method([], $req);

        echo $resp->serializeToString();
    } catch (\Twirp\Error $e) {
        file_put_contents('php://stderr', $e->getErrorCode());
    }
}
