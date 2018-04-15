Best Practices
==============

This page contains some best practices related to using TwirPHP in general.
Make sure to check out the `official best practices guide`_ as well for Twirp and protobuf related practices.

Folder/Package Structure
------------------------

There are three types of "resources" to consider in case of a PHP projects using TwirPHP:

* proto files
* generated code
* service implementation

Following common PHP packaging practice the recommended folder structure is:

.. code-block:: text

    /generated
        /<namespace>
            // generated files
    /src
        /<namespace>
            // service implementation
    /proto
        service.proto


Build tool for code generation
------------------------------

Make sure to properly document how the code generation works.

Even better: use some sort of build tool to collect all proto generation commands.
In case of PHP, that tool can be `Composer`_ itself.

.. code-block:: json

    {
        "scripts": {
            "proto": [
                "protoc -I . --twirp_out=generated --php_out=generated proto/service.proto"
            ]
        }
    }

.. code-block:: bash

    $ composer proto


.. _official best practices guide: https://twitchtv.github.io/twirp/docs/best_practices.html
.. _Composer: https://getcomposer.org
