Custom HTTP Headers
===================

Sometimes, you need to send custom HTTP headers.

From Twirp's perspective, "headers" are just metadata since HTTP is a lower level, transport layer.
But since Twirp is primarily used with HTTP, sometimes you might need to send or receive some information from that layer too.


Client side
-----------

Send HTTP Headers with client requests
++++++++++++++++++++++++++++++++++++++

Use `Twirp\Context::withHttpRequestHeaders` to attach a map of headers to the context:

.. code-block:: php

    <?php

    // Given a client ...
    $client = new HaberdasherClient($addr);

    // Given some headers ...
    $headers = [
        'Twitch-Authorization' => 'uDRlDxQYbFVXarBvmTncBoWKcZKqrZTY',
        'Twitch-Client-ID' => 'FrankerZ',
    ];

    // Attach the headers to a context
    $ctx = [];
    $ctx = Twirp\Context::withHttpRequestHeaders($ctx, $headers);

    // And use the context in the request. Headers will be included in the request!
    $resp = $client—>MakeHat($ctx, new Size());


Read HTTP Headers from responses
++++++++++++++++++++++++++++++++

Twirp client responses are structs that depend only on the protobuf response.
HTTP headers can not be used by the Twirp client in any way.

However, remember that the TwirPHP client is instantiated with an `HTTPlug`_ client,
which can be anything that implements the minimal interface.
For example you could configure a `PluginClient`_ and read the headers in a plugin.


Server side
-----------

Send HTTP Headers on server responses
+++++++++++++++++++++++++++++++++++++

In your server implementation you can set HTTP headers using `Twirp\Context::withHttpResponseHeader`.


.. code-block:: php

    <?php

    public function makeHat(array $ctx, Size $size)
    {
        Twirp\Context::withHttpResponseHeader($ctx, 'Cache-Control', 'public, max-age=60');

        $hat = new Hat();

        return $hat;
    }


Read HTTP Headers from requests
+++++++++++++++++++++++++++++++

TwirPHP server methods are abstracted away from HTTP, therefore they don't have direct access to HTTP Headers.

However, they receive the PSR-7 server attributes as the context
that can be modified by HTTP middleware before being used by the Twirp method.

For example, lets say you want to read the 'User-Agent' HTTP header inside a twirp server method.
You might write this middleware:

.. code-block:: php

    <?php

    use Psr\Http\Message\ServerRequestInterface;

    public function handle(ServerRequestInterface $request)
    {
        $request = $request->withAttribute('user-agent', $request->getHeaderLine('User-Agent'));

        return $this->server->handle($request);
    }


.. _HTTPlug: http://httplug.io/
.. _PluginClient: http://docs.php-http.org/en/latest/plugins/index.html
