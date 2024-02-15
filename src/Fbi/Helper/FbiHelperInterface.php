<?php

namespace Oneup\ContaoFullBackgroundImagesBundle\Fbi\Helper;

use Contao\PageModel;

interface FbiHelperInterface
{
    public function findAll(PageModel $pageModel);
}
