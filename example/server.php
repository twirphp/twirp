<?php

require __DIR__ . '/../lib/vendor/autoload.php';

$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();

$handler = new \Twitch\Twirp\Example\HaberdasherServer(new \Twirphp\Example\Haberdasher());

$response = $handler->handle($request);

if (!headers_sent()) {
    // status
    header(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $response->getStatusCode(), $response->getReasonPhrase()), true, $response->getStatusCode());
    // headers
    foreach ($response->getHeaders() as $header => $values) {
        foreach ($values as $value) {
            header($header . ': ' . $value, false, $response->getStatusCode());
        }
    }
}
echo $response->getBody();
