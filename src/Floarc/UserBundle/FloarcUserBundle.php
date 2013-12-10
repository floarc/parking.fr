<?php

namespace Floarc\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FloarcUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
