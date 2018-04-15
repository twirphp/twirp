TwirPHP: PHP port of Twirp, Twitch's RPC framework
==================================================

`Twirp`_ is a "Twirp is a simple RPC framework built on `protobuf`_."
Unfortunately (or not?) it only supports Go and Python officially.
While in the modern world it may be enough for most of the projects,
there is still a considerable number of PHP-based softwares out there.

This project is a PHP port of Twirp supporting both server and client side.
It generates code the same way as Twirp does and follows the same conventions.
Because of that this documentation only contains minimal information about how
Twirp works internally. To learn more about it, you should check the following
resources published by the Twirp developers themselves:

* `Official Documentation <https://twitchtv.github.io/twirp/>`_
* `Introductory Post <https://blog.twitch.tv/twirp-a-sweet-new-rpc-framework-for-go-5f2febbf35f>`_



.. toctree::
    :hidden:

    TwirPHP <self>

.. toctree::
    :hidden:
    :caption: Getting started

    getting-started/overview
    getting-started/installation
    getting-started/usage
    getting-started/best-practices

.. toctree::
    :hidden:
    :caption: Beyond the basics

    advanced/other-services


.. _Twirp: https://twitchtv.github.io/twirp/
.. _protobuf: https://developers.google.com/protocol-buffers/
