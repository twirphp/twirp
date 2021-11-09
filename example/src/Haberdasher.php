<?php

namespace Twirp\Example;

use Twitch\Twirp\Example\Hat;
use Twitch\Twirp\Example\Size;

final class Haberdasher implements \Twitch\Twirp\Example\Haberdasher
{
    public function MakeHat(array $ctx, Size $size): Hat
    {
        $hat = new Hat();
        $hat->setSize($size->getInches());
        $hat->setColor('golden');
        $hat->setName('crown');

        return $hat;
    }
}
