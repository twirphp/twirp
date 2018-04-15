Installation
============

You'll need a few things to install TwirPHP:

* The protobuf compiler: ``protoc``
* TwirPHP plugin for protobuf to generate code
* TwirPHP shared PHP library
* An HTTP client and PSR-7 implementation of your choice


Install protoc
--------------

``protoc`` is used to generate code from protobuf definitions.
The easiest way to install it is downloading the precompiled binary from the
`Github Releases <https://github.com/google/protobuf/releases>`_ page.

You can also install it via Homebrew on MacOS:

.. code-block:: bash

    $ brew install protobuf


Install TwirPHP protoc plugin
-----------------------------

Just like in case of ``protoc``, the easiest way to install the plugin
by downloading it from the `Github Releases <https://github.com/twirphp/twirp/releases>`_ page.

Make sure to save the binary exactly it is found in the downloaded archive.
Also, make sure you place the binary in your ``$PATH``, otherwise you will have to
tell ``protoc`` where you saved the plugin:


.. code-block:: bash

    $ protoc --plugin=protoc-gen-twirp_php=path/to/protoc-gen-twirp_php ...


Alternatively you can install the plugin from source. For that you are going to need
`dep`_ to be installed.

.. code-block:: bash

    $ go get github.com/twirphp/twirp/protoc-gen-twirp_php
    $ cd $GOROOT/src/github.com/twirphp/twirp/protoc-gen-twirp_php
    $ dep ensure
    $ go install

The commands above will put the binary in your ``$GOBIN`` path which is usually a good idea to be included
in your ``$PATH`` prefix.


Install the shared library
--------------------------

In order to make the generated code work (in a PHP project) you need to install the shared library
via `Composer`_.

.. code-block:: bash

    $ composer require twirp/twirp


HTTP Client and PSR-7 implementation
------------------------------------

The generated code relies on `PSR-7`_ (HTTP Message standard) and the related `message factories`_.
Furthermore, the generated client relies on  `HTTPlug`_ (HTTP Client standard).
As such, you will have to install some additional dependencies of your choice.
If you do HTTP Client stuff in your project, chances are that some of them are already installed.

An example set of dependencies for server usage:

.. code-block:: bash

    $ composer require guzzlehttp/psr7 php-http/message


And an example for client usage:

.. code-block:: bash

    $ composer require guzzlehttp/psr7 php-http/message php-http/guzzle6-adapter

You can find packages that implement the above interfaces on `Packagist`_:

* `PSR-7 implementation <https://packagist.org/providers/psr/http-message-implementation>`_
* `Message Factory implementation <https://packagist.org/providers/php-http/message-factory-implementation>`_
* `HTTP Client implementation <https://packagist.org/providers/php-http/client-implementation>`_


.. _dep: https://golang.github.io/dep/
.. _Composer: https://getcomposer.org
.. _PSR-7: http://www.php-fig.org/psr/psr-7/
.. _message factories: https://github.com/php-http/message-factory
.. _HTTPlug: http://httplug.io/
.. _Packagist: https://packagist.org
