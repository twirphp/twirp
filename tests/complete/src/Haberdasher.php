<?php

namespace Twirp\Tests\Complete;

use Twirp\Tests\Complete\Proto\Hat;
use Twirp\Tests\Complete\Proto\Size;

final class Haberdasher implements \Twirp\Tests\Complete\Proto\Haberdasher
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
