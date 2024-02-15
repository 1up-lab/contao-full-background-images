<?php

namespace Oneup\ContaoFullBackgroundImagesBundle\Fbi\Helper\Dca;

use Contao\Backend;

class DcaHelper extends Backend
{
    public function __construct()
    {
        parent::__construct();

        $this->import('BackendUser', 'User');
    }

    public function getElementTemplates()
    {
        return Backend::getTemplateGroup('fbi_');
    }
}
