Using PSR-15
============

`PSR-15`_ is a standard describing server request handlers and middlewares for PHP.

In some cases Twirp might not be the primary receiver of requests or simply you might want to add some
extra logic to the HTTP flow (for example attaching some headers to the Twirp context).
Either way, PSR-15 has a great ecosystem around it which helps you with both cases.


Routing requests
----------------

When a Twirp service is not the primary target of requests in an application,
you probably want to register it as a sort of "controller" and handle the routing outside of it.

Given an imaginary router, you might write some code like this:

.. code-block:: php

    <?php

    $server = new \Twitch\Twirp\Example\HaberdasherServer(new \Twirphp\Example\Haberdasher());

    $router->register('POST', \Twitch\Twirp\Example\HaberdasherServer::PATH_PREFIX, [$server, 'handle']);

The code above registers the Twirp server as a request handler for it's path prefix in the router.
The syntax of course can be different based on the router implementation.

How is this connected to PSR-15? In PHP, routing is often a step of a middleware chain.
For example you could use `FastRoute`_ together with its `middleware <https://github.com/middlewares/fast-route>`_.


Middlewares
-----------

When Twirp `is` the primary target of requests, it can simply be the last element in your middleware chain
to be the final request handler.
To make a Twirp server compatible with PSR-15 you need a simple wrapper class that implements the PSR-15
request handler interface.

.. code-block:: php

    <?php

    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Server\RequestHandlerInterface;
    use Twirp\RequestHandler;

    final class Psr15RequestHandler implements RequestHandlerInterface
    {
        /**
         * @var RequestHandler
         */
        private $server;

        public function __construct(RequestHandler $server)
        {
            $this->server = $server;
        }

        public function handle(ServerRequestInterface $request): ResponseInterface
        {
            return $this->server->handle($request);
        }
    }

.. note:: TwirPHP's ``RequestHandler`` interface is a backport from PSR-15 to make it PHP 5.x compatible.

    It may eventually be deprecated in favor of the original interface.

The wrapper class can easily be registered in any PSR-15 compatible middleware chain.


.. _PSR-15: https://www.php-fig.org/psr/psr-15/
.. _FastRoute: https://github.com/nikic/FastRoute
