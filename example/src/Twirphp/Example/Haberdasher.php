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
