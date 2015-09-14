<?php

namespace Oneup\Contao\Fbi\Helper\Dca;

class DcaHelper extends \Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function getElementTemplates()
    {
        return $this->getTemplateGroup('fbi_');
    }
}
