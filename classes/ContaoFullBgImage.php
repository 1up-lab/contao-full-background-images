<?php

namespace Oneup;

class ContaoFullBgImage extends \Frontend
{
    protected $objFiles = null;
    protected $mode;
    protected $timeout;
    protected $speed;
    protected $sortBy;
    protected $order;
    protected $objPageBg;

    public function generate(\PageModel $objPage, \LayoutModel $objLayout, \PageRegular $objPageRegular)
    {
        if (TL_MODE == 'BE') return ;

        $this->objPageBg = $this->searchForBackgroundImages($objPage);

        // Return if there are no files
        if (!$this->objPageBg) return ;

        // Get the file entries from the database
        $this->objFiles = \FilesModel::findMultipleByUuids($this->objPageBg->paSRC);

        if ($this->objFiles === null)
        {
            if (!\Validator::isUuid($this->objPageBg->paSRC[0]))
            {
                \System::log($GLOBALS['TL_LANG']['ERR']['version2format'], __METHOD__, TL_ERROR);
            }

            return;
        }

        $this->order = $this->objPageBg->paOrder;

        $this->compile($objPage);
    }

    protected function searchForBackgroundImages($objPage)
    {
        $objPage->paSRC = deserialize($objPage->paSRC);

        if ($objPage->type === 'root') {
            $objPage->pam = $objPage->pam_root;
            $objPage->paSpeed = $objPage->paRootSpeed;
            $objPage->paTimeout = $objPage->paRootTimeout;
        }

        switch($objPage->pam) {
            case 'disable':
                $objPage = null;
                break;

            case '':
            case 'inherit':
                if (!$objPage->pid) return null;
                $objPage = $this->searchForBackgroundImages(\PageModel::findOneBy('id', $objPage->pid));
                break;

            case 'choose':
                if (!is_array($objPage->paSRC) || empty($objPage->paSRC)) {
                    $objPage = null;
                }
                break;
        }

        return $objPage;
    }

    protected function applySettings(\PageModel $objPage)
    {
        if ($objPage->type === 'root') {
            $objPage->pam = $objPage->pam_root;
            $objPage->paSpeed = $objPage->paRootSpeed;
            $objPage->paTimeout = $objPage->paRootTimeout;
        }

        $value = $objPage->pam . (int) $objPage->paOverwrite;

        $pageWithSettings = $this->findParentWithSettings($objPage, 'paRootEnableNav');

        $this->nav = (int) $pageWithSettings->paRootEnableNav;
        $this->navClick = (int) $pageWithSettings->paRootNavClick;
        $this->navPrevNext = (int) $pageWithSettings->paRootNavPrevNext;
        $this->centeredX = (int) $pageWithSettings->paRootCenteredX;
        $this->centeredY = (int) $pageWithSettings->paRootCenteredY;

        switch((string) $value)
        {
            case '':
            case '0':
            case 'inherit':
            case 'inherit0':
            case 'choose0':
                // take parent settings
                $pageWithSettings = $this->findParentWithSettings($objPage);

                $this->mode    = $pageWithSettings->paImgMode;
                $this->speed   = $pageWithSettings->paSpeed;
                $this->timeout = $pageWithSettings->paTimeout;
                $this->sortBy  = $pageWithSettings->sortBy;
                $this->order   = $pageWithSettings->paOrder;
                break;
            case '1':
            case 'inherit1':
            case 'choose1':
                if ($objPage->paSpeed == '') {
                    $pageWithSettings   = $this->findParentWithSettings($objPage, 'paSpeed');
                    $objPage->paSpeed   = $objPage->paSpeed   == '' ? $pageWithSettings->paSpeed   : $objPage->paSpeed;
                }

                if ($objPage->paTimeout == '') {
                    $pageWithSettings = $this->findParentWithSettings($objPage, 'paTimeout');
                    $objPage->paTimeout = $objPage->paTimeout == '' ? $pageWithSettings->paTimeout : $objPage->paTimeout;
                }

                if ($objPage->sortBy == '') {
                    $pageWithSettings = $this->findParentWithSettings($objPage, 'sortBy');
                    $objPage->sortBy = $objPage->sortBy == '' ? $pageWithSettings->sortBy : $objPage->sortBy;
                }

                if ($objPage->order == '') {
                    $objPage->order = $objPage->paOrder == '' ? $pageWithSettings->paOrder : $objPage->paOrder;
                }

                $this->mode    = $objPage->paImgMode;
                $this->sortBy  = $objPage->sortBy;
                $this->speed   = $objPage->paSpeed;
                $this->timeout = $objPage->paTimeout;
                $this->order   = $objPage->order;

                break;
        }
    }

    protected function findParentWithSettings(\PageModel $objPage, $property = null)
    {
        if ($objPage->type === 'root') {
            $objPage->pam = $objPage->pam_root;
            $objPage->paSpeed = $objPage->paRootSpeed;
            $objPage->paTimeout = $objPage->paRootTimeout;
            $objPage->centeredX = $objPage->paRootCenteredX;
            $objPage->centeredY = $objPage->paRootCenteredY;
        }

        if ($property) {
            if ($objPage->{$property} != '') return $objPage;
        }

        if ($objPage->paSpeed && $objPage->paTimeout && $objPage->sortBy != '') {
            return $objPage;
        }

        if (!$objPage->pid && $objPage->type === 'root') return $objPage;

        return $this->findParentWithSettings(\PageModel::findOneBy('id', $objPage->pid), $property);
    }

    protected function compile(\PageModel $objPage)
    {
        $images = array();
        $auxDate = array();

        $objFiles = $this->objFiles;

        $this->applySettings($objPage);

        while ($objFiles->next()) {

            // Continue if the files has been processed or does not exist
            if (isset($images[$objFiles->path]) || !file_exists(TL_ROOT . '/' . $objFiles->path))
            {
                continue;
            }

            // Single files
            if ($objFiles->type == 'file')
            {
                $objFile = new \File($objFiles->path, true);

                if (!$objFile->isGdImage)
                {
                    continue;
                }

                $arrMeta = $this->getMetaData($objFiles->meta, $objPage->language);

                // Use the file name as title if none is given
                if ($arrMeta['title'] == '')
                {
                    $arrMeta['title'] = specialchars(str_replace('_', ' ', $objFile->filename));
                }

                // Add the image
                $images[$objFiles->path] = array
                (
                    'id'        => $objFiles->id,
                    'uuid'      => $objFiles->uuid,
                    'name'      => $objFile->basename,
                    'singleSRC' => $objFiles->path,
                    'alt'       => $arrMeta['title'],
                    'imageUrl'  => $arrMeta['link'],
                    'caption'   => $arrMeta['caption']
                );

                $auxDate[] = $objFile->mtime;
            }

            // Folders
            else
            {
                $objSubfiles = \FilesModel::findByPid($objFiles->uuid);

                if ($objSubfiles === null)
                {
                    continue;
                }

                while ($objSubfiles->next())
                {
                    // Skip subfolders
                    if ($objSubfiles->type == 'folder')
                    {
                        continue;
                    }

                    $objFile = new \File($objSubfiles->path, true);

                    if (!$objFile->isGdImage)
                    {
                        continue;
                    }

                    $arrMeta = $this->getMetaData($objSubfiles->meta, $objPage->language);

                    // Use the file name as title if none is given
                    if ($arrMeta['title'] == '')
                    {
                        $arrMeta['title'] = specialchars(str_replace('_', ' ', $objFile->filename));
                    }

                    // Add the image
                    $images[$objSubfiles->path] = array
                    (
                        'id'        => $objSubfiles->id,
                        'uuid'      => $objSubfiles->uuid,
                        'name'      => $objFile->basename,
                        'singleSRC' => $objSubfiles->path,
                        'alt'       => $arrMeta['title'],
                        'imageUrl'  => $arrMeta['link'],
                        'caption'   => $arrMeta['caption']
                    );

                    $auxDate[] = $objFile->mtime;
                }
            }
        }

        // Sort array
        switch ($this->sortBy)
        {
            default:
            case 'name_asc':
                uksort($images, 'basename_natcasecmp');
                break;

            case 'name_desc':
                uksort($images, 'basename_natcasercmp');
                break;

            case 'date_asc':
                array_multisort($images, SORT_NUMERIC, $auxDate, SORT_ASC);
                break;

            case 'date_desc':
                array_multisort($images, SORT_NUMERIC, $auxDate, SORT_DESC);
                break;

            case 'meta': // Backwards compatibility
            case 'custom':
                if ($this->order != '')
                {
                    $tmp = deserialize($this->order);

                    if (!empty($tmp) && is_array($tmp))
                    {
                        // Remove all values
                        $arrOrder = array_map(function(){}, array_flip($tmp));

                        // Move the matching elements to their position in $arrOrder
                        foreach ($images as $k=>$v)
                        {
                            if (array_key_exists($v['uuid'], $arrOrder))
                            {
                                $arrOrder[$v['uuid']] = $v;
                                unset($images[$k]);
                            }
                        }

                        // Append the left-over images at the end
                        if (!empty($images))
                        {
                            $arrOrder = array_merge($arrOrder, array_values($images));
                        }

                        // Remove empty (unreplaced) entries
                        $images = array_values(array_filter($arrOrder));
                        unset($arrOrder);
                    }
                }
                break;

            case 'random':
                shuffle($images);
                break;
        }

        $images = array_values($images);
        $intMaxWidth = $GLOBALS['TL_CONFIG']['maxImageWidth'];
        $objImages = array();
        $imageIndex = 0;

        if (count($images))
        {
            if ($this->mode === 'paSingleRandom') {
                mt_srand();
                $imageIndex = mt_rand(0, count($images)-1);
            }

            foreach($images as $image) {
                $objCell = new \stdClass();
                $this->addImageToTemplate($objCell, $image, $intMaxWidth);
                $objImages[] = $objCell;
            }

            if ($this->mode === 'paSingle' || $this->mode === 'paSingleRandom') {
                $objImages = array($objImages[$imageIndex]);
            }

            $strTemplate = 'oneup_ct_fullbgimage';

            $objTemplate = new \FrontendTemplate($strTemplate);
            $objTemplate->images = implode(',', array_map(function($objImage){return '"' . $objImage->src . '"';}, $objImages));
            $objTemplate->timeout = (int) $this->timeout;
            $objTemplate->speed = (int) $this->speed;
            $objTemplate->nav = $this->nav ? 'true' : 'false';
            $objTemplate->navClick = $this->navClick ? 'true' : 'false';
            $objTemplate->navPrevNext = $this->navPrevNext ? 'true' : 'false';
            $objTemplate->centeredX = $this->centeredX ? 'true' : 'false';
            $objTemplate->centeredY = $this->centeredY ? 'true' : 'false';

            // add javascript and css files
            $GLOBALS['TL_BODY'][] = $objTemplate->parse();
            $GLOBALS['TL_CSS'][] = 'system/modules/full-background-images/assets/css/style.css||static';
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/full-background-images/assets/js/eventListener.polyfill.js|static';
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/full-background-images/assets/js/jquery.backstretch.min.js|static';
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/full-background-images/assets/js/fullbackground.js|static';
        }
    }
}
