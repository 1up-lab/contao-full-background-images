<?php

namespace Oneup\ContaoFullBackgroundImagesBundle\Helper;

use Contao\PageModel;

interface FbiHelperInterface
{
    public function findAll(PageModel $pageModel);
}
