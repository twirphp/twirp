Overview
========

Similar to Twirp itself, TwirPHP comes with two components:

* Code generator library (written in Go)
* Shared PHP library

The code generator is used to generate the Twirp specific server and client files.
The generated code tries to be as self-contained as possible,
keeping the runtime library small.
This concept is present in Twirp itself as well.
The reason behind is to prevent accidental backward incompatible changes in the
shared library break your code.
See more about this in the `introductory post`_.

The shared library contains common interfaces and some code that is part of the
protocol itself. It can be installed via `Composer`_.


Versioning
----------

TwirPHP is versioned separately from Twirp to ensure that it's lifecycle does not depend
on the original library.

In order to track which version of the `Twirp specification`_ is supported,
please refer to the `TWIRP_VERSION`_ file in the repository.

Twirp version changes will always trigger a new major version,
but it might also contain backward incompatible changes of the library itself,
so keep an eye on the `Change Log`_.


.. _introductory post: https://blog.twitch.tv/twirp-a-sweet-new-rpc-framework-for-go-5f2febbf35f#d1bb
.. _Composer: https://getcomposer.org
.. _Twirp specification: https://twitchtv.github.io/twirp/docs/spec_v5.html
.. _TWIRP_VERSION: https://github.com/twirphp/twirp/blob/master/TWIRP_VERSION
.. _Change Log: https://github.com/twirphp/twirp/blob/master/CHANGELOG.md
