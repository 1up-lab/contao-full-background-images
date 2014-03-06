<?php

namespace Oneup;

class ModulePotentialAvenger extends \Module
{
    protected $objFiles = null;
    protected $strTemplate = 'mod_potential_avenger';

    public function generate()
    {
        global $objPage;

        if (TL_MODE == 'BE') return '';

        $objPage->paSRC = deserialize($objPage->paSRC);

        // Return if there are no files
        if (!is_array($objPage->paSRC) || empty($objPage->paSRC) || $objPage->usePotentialAvenger === '')
        {
            // TODO: search recursive up parent
            return '';
        }

        // Get the file entries from the database
        $this->objFiles = \FilesModel::findMultipleByUuids($objPage->paSRC);

        if ($this->objFiles === null)
        {
            if (!\Validator::isUuid($objPage->paSRC[0]))
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

        foreach($images as $image) {
            $objCell = new \stdClass();

            $this->addImageToTemplate($objCell, $image, $intMaxWidth);

            $objImages[] = $objCell;
        }

        $strTemplate = 'potential_avenger';

        $objTemplate = new \FrontendTemplate($strTemplate);
        $objTemplate->images = implode(',', array_map(function($objImage){return '"' . $objImage->src . '"';}, $objImages));

        $GLOBALS['TL_BODY'][] = $objTemplate->parse();
        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/potential-avenger/assets/js/jquery.bcat.bgswitcher.js|static';
        $GLOBALS['TL_CSS'][] = 'system/modules/potential-avenger/assets/css/bgswitcher.css||static';
    }
}
