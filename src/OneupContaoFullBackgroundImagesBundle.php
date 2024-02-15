<?php

declare(strict_types=1);

namespace Oneup\ContaoFullBackgroundImagesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OneupContaoFullBackgroundImagesBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
