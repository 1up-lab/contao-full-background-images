<?php

namespace Oneup\ContaoFullBackgroundImagesBundle\Helper\Dca;

use Contao\Backend;

class DcaHelper
{
    public function getElementTemplates(): array
    {
        return Backend::getTemplateGroup('fbi_');
    }
}
