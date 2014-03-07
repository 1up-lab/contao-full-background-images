<?php

namespace Oneup;

class ModulePotentialAvenger extends \Module
{
    protected $objFiles = null;
    protected $mode;
    protected $timeout;
    protected $speed;
    protected $objPageBg;
    protected $strTemplate = 'mod_potential_avenger';

    protected function searchForBackgroundImages($objPage)
    {
        $objPage->paSRC = deserialize($objPage->paSRC);

        switch($objPage->pam) {
            case 'disable':
                $objPage = null;
                break;

            case '':
            case 'inherit':
                if ($objPage->pid == 0) return null;
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

    public function generate()
    {
        global $objPage;

        if (TL_MODE == 'BE') return '';

        $this->objPageBg = $this->searchForBackgroundImages($objPage);

        // Return if there are no files
        if (!$this->objPageBg) return '';

        // Get the file entries from the database
        $this->objFiles = \FilesModel::findMultipleByUuids($this->objPageBg->paSRC);

        if ($this->objFiles === null)
        {
            if (!\Validator::isUuid($this->objPageBg->paSRC[0]))
            {
                return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
            }

            return '';
        }

        return parent::generate();
    }

    protected function compile()
    {
        global $objPage;

        $images = array();
        $auxDate = array();

        $objFiles = $this->objFiles;

        $this->mode    = $this->paImgMode;
        $this->speed   = $this->paSpeed;
        $this->timeout = $this->paTimeout;

        if ($overwrite = (int) $this->objPageBg->overwriteModule) {
            $this->mode    = $this->objPageBg->paImgMode ? $this->objPageBg->paImgMode : $this->paImgMode;
            $this->speed   = $this->objPageBg->paSpeed   ? $this->objPageBg->paSpeed   : $this->paSpeed;
            $this->timeout = $this->objPageBg->paTimeout ? $this->objPageBg->paTimeout : $this->paTimeout;
        }

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
                if ($this->paOrder != '')
                {
                    $tmp = deserialize($this->paOrder);

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

        $strTemplate = 'potential_avenger';

        $objTemplate = new \FrontendTemplate($strTemplate);
        $objTemplate->images = implode(',', array_map(function($objImage){return '"' . $objImage->src . '"';}, $objImages));
        $objTemplate->timeout = (int) $this->timeout;
        $objTemplate->speed = (int) $this->speed;

        // add javascript and css files
        $GLOBALS['TL_BODY'][] = $objTemplate->parse();
        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/potential-avenger/assets/js/jquery.backstretch.min.js|static';
    }
}
