Usage Example
=============

This page contains a full example about using TwirPHP on both server and client side.
Much of it is based on the `original usage example <https://twitchtv.github.io/twirp/docs/example.html>`_,
so you might want to check that out as well.
The complete example is available under the `example <https://github.com/twirphp/twirp/tree/master/example>`_
directory of the project's git repository.

Before moving forward, make sure to check the :doc:`installation` guide as well.

.. contents::
    :local:


Write a Protobuf service definition
-----------------------------------

Write a protobuf definition for your messages and your service and put it in ``proto/service.proto``.
The following example is taken from the `original Twirp documentation <https://twitchtv.github.io/twirp/docs/example.html#write-a-protobuf-service-definition>`_.

.. code-block:: protobuf

    syntax = "proto3";

    package twirp.example.haberdasher;
    option go_package = "haberdasher";

    // Haberdasher service makes hats for clients.
    service Haberdasher {
      // MakeHat produces a hat of mysterious, randomly-selected color!
      rpc MakeHat(Size) returns (Hat);
    }

    // Size of a Hat, in inches.
    message Size {
      int32 inches = 1; // must be > 0
    }

    // A Hat is a piece of headwear made by a Haberdasher.
    message Hat {
      int32 inches = 1;
      string color = 2; // anything but "invisible"
      string name = 3; // i.e. "bowler"
    }


Generate code
-------------

To generate code run the ``protoc`` compiler pointed at your service definition file:

.. code-block:: bash

    $ mkdir -p generated
    $ protoc -I . --twirp_php_out=generated --php_out=generated ./proto/service.proto

This will generate the standard PHP messages along with the Twirp specific files.


Implement the server
--------------------

Now that everything is in place, it's time to implement the server implementing the service interface
(``Twitch\Twirp\Example\Haberdasher`` in this case).

.. code-block:: php

    <?php

    namespace Twirphp\Example;

    use Twitch\Twirp\Example\Hat;
    use Twitch\Twirp\Example\Size;

    final class Haberdasher implements \Twitch\Twirp\Example\Haberdasher
    {
        public function makeHat(array $ctx, Size $size)
        {
            $hat = new Hat();
            $hat->setSize($size->getInches());
            $hat->setColor('golden');
            $hat->setName('crown');

            return $hat;
        }
    }


.. _run-server:

Run the server
--------------

To run the server you need to setup some sort of application entrypoint that processes incoming requests as `PSR-7`_
messages. It is recommended that you use some sort of dispatcher/emitter,
like the ``SapiEmitter`` bundled with `Zend Diactoros`_, but the following example
works perfectly as well:

.. code-block:: php

    <?php

    require __DIR__.'/vendor/autoload.php';

    $server = new \Twitch\Twirp\Example\HaberdasherServer(new \Twirphp\Example\Haberdasher());

    $request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();

    $response = $server->handle($request);

    if (!headers_sent()) {
        // status
        header(sprintf(
            'HTTP/%s %s %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()),
            true,
            $response->getStatusCode()
        );

        // headers
        foreach ($response->getHeaders() as $header => $values) {
            foreach ($values as $value) {
                header($header.': '.$value, false, $response->getStatusCode());
            }
        }
    }

    echo $response->getBody();


Use the client
--------------

Client stubs are automatically generated, hooray!

The original library offers two clients to be generated differing in the underlying serialization: JSON and Protobuf.
This library only offers Protobuf as per the official recommendation.

.. note:: This may change in the future based on demand.

Using the client is quite trivial, you only need to pass an endpoint to the generated client:

.. code-block:: php

    <?php

    $client = new \Twitch\Twirp\Example\HaberdasherClient('http://localhost:8080');

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

.. warning:: When no scheme is present in the endpoint, the client falls back to `HTTP`.


.. _PSR-7: http://www.php-fig.org/psr/psr-7/
.. _Zend Diactoros: https://zendframework.github.io/zend-diactoros/usage/#server-side-applications
