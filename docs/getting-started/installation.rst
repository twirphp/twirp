Installation
============

You'll need a few things to install TwirPHP:

.. contents::
    :local:


Protobuf compiler
-----------------

``protoc`` is used to generate code from protobuf definitions.
The easiest way to install it is downloading the precompiled binary from the
`Github Releases <https://github.com/google/protobuf/releases>`_ page.

You can also install it via Homebrew on MacOS:

.. code-block:: bash

    $ brew install protobuf


TwirPHP protoc plugin
---------------------

Just like in case of ``protoc``, the easiest way to install the plugin
is downloading it from the `Github Releases <https://github.com/twirphp/twirp/releases>`_ page.

Alternatively you can use the following oneliner to install the plugin:

.. code-block:: bash

    curl -Ls https://git.io/twirphp | bash -b path/to/bin


Make sure to save the binary with the same name as it is found in the downloaded archive.
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


Protobuf PHP library
--------------------

As described in the `protobuf PHP library README <https://github.com/google/protobuf/tree/master/php>`_
there are two ways to install protobuf:

* C extension
* native PHP package

The C extension provides better performance obviously, so it is recommended to be used,
on the other hand the PHP package provides better portability.

The extension can be installed from the linked repository above or via Pecl:

.. code-block:: bash

    $ sudo pecl install protobuf-{VERSION}

The PHP package can be installed via `Composer`_:

.. code-block:: bash

    $ composer require google/protobuf


Shared PHP library
------------------

In order to make the generated code work (in a PHP project) you need to install the (minimal) shared library
via `Composer`_.

.. code-block:: bash

    $ composer require twirp/twirp


HTTP Client and PSR-7 implementation
------------------------------------

The generated code relies on the following standard HTTP interfaces:

* `PSR-7`_ (HTTP Message)
* `PSR-15`_ (HTTP Server Request Handler)
* `PSR-17`_ (HTTP Factory)
* `PSR-18`_ (HTTP Client)

Choosing the right implementations for your project is up to you.
If you do HTTP stuff in your project, chances are that some of them are already installed.

An example set of dependencies for server usage:

.. code-block:: bash

    $ composer require guzzlehttp/psr7 http-interop/http-factory-guzzle


And for client usage:

.. code-block:: bash

    $ composer require guzzlehttp/psr7 http-interop/http-factory-guzzle php-http/guzzle6-adapter

You can find packages that implement the above interfaces on `Packagist`_:

* `PSR-7 implementation <https://packagist.org/providers/psr/http-message-implementation>`_
* `PSR-17 implementation <https://packagist.org/providers/psr/http-factory-implementation>`_
* `psr-18 implementation <https://packagist.org/providers/psr/http-client-implementation>`_


Quickstart
----------

From the above guide it is clear that installing TwirPHP is not trivial. It has multiple components and
external dependencies. To make installing these dependencies easier, there is a quickstart metapackage which
can be installed via `Composer`_:

.. code-block:: bash

    $ composer require twirp/quickstart

It installs:

* the protobuf runtime library
* Guzzle PSR-7 (and it's factories)
* Guzzle HTTP Client


.. _dep: https://golang.github.io/dep/
.. _Composer: https://getcomposer.org
.. _PSR-7: http://www.php-fig.org/psr/psr-7/
.. _PSR-15: http://www.php-fig.org/psr/psr-15/
.. _PSR-17: http://www.php-fig.org/psr/psr-17/
.. _PSR-18: http://www.php-fig.org/psr/psr-18/
.. _message factories: https://github.com/php-http/message-factory
.. _Packagist: https://packagist.org
