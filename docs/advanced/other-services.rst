Serving multiple Twirp services together
========================================

In some cases you might want to serve not just one, but multiple services from one application.
The generated code contains a simple server implementation which lets you mux different services.

.. code-block:: php

    <?php

    $server = new \Twitch\Twirp\Example\Server();

    // register services
    $server->registerServer(
        \Twitch\Twirp\Example\HaberdasherServer::PATH_PREFIX,
        new \Twitch\Twirp\Example\HaberdasherServer(
            new \Twirphp\Example\Haberdasher()
        )
    );

Both the server and service server implement the same ``Twirp\RequestHandler`` interface, so you can use the same code
as in the :ref:`run-server` usage example:

.. code-block:: php

    <?php

    // ...
    $response = $server->handle($request);
