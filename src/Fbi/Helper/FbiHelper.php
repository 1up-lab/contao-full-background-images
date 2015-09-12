<?php

namespace Oneup\Contao\Fbi\Helper;

class FbiHelper implements FbiHelperInterface
{
    public function findAll(\PageModel $pageModel)
    {
        $pageModel->fbiSRC = deserialize($pageModel->fbiSRC);

        switch ($pageModel->fbi) {
            case 'disable':
                $pageModel = null;
                break;

            case '':
            case 'inherit':
                if (!$pageModel->pid) {
                    return null;
                }
                $pageModel = $this->findAll(\PageModel::findOneBy('id', $pageModel->pid));
                break;

            case 'choose':
                if (!is_array($pageModel->fbiSRC) || empty($pageModel->fbiSRC)) {
                    $pageModel = null;
                }
                break;
        }

        return $pageModel;
    }
}
