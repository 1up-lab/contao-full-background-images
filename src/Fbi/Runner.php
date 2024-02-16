<?php

namespace Oneup\ContaoFullBackgroundImagesBundle\Fbi;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\File;
use Contao\FilesModel;
use Contao\Frontend;
use Contao\FrontendTemplate;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;
use Contao\StringUtil;
use Model\Collection;
use Oneup\ContaoFullBackgroundImagesBundle\Fbi\Helper\FbiHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Runner
{
    protected $pageWithBackgrounds;

    /** @var Collection */
    protected $backgroundFiles;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
        private readonly FbiHelper    $backgroundHelper,
    ) {
    }

    public function __invoke(PageModel $page, LayoutModel $layout, PageRegular $pageRegular): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request && $this->scopeMatcher->isBackendRequest($request)) {
            return;
        }

        $this->pageWithBackgrounds = $this->backgroundHelper->findAll($page);

        // Return if there are no files
        if (!$this->pageWithBackgrounds) {
            return;
        }

        // Get the file entries from the database
        $this->backgroundFiles = FilesModel::findMultipleByUuids($this->pageWithBackgrounds->fbiSRC);

        if ($this->backgroundFiles === null) {
            return;
        }

        $this->fbiTemplate = $this->pageWithBackgrounds->fbiTemplate;
        $this->caption = (int)$this->pageWithBackgrounds->fbiImgCaption;
        $this->mode = $this->pageWithBackgrounds->fbiImgMode;
        $this->sortBy = $this->pageWithBackgrounds->sortBy;
        $this->speed = $this->pageWithBackgrounds->fbiSpeed;
        $this->timeout = $this->pageWithBackgrounds->fbiTimeout;
        $this->order = $this->pageWithBackgrounds->fbiOrder;
        $this->fbiLimit = (int)$this->pageWithBackgrounds->fbiLimit;
        $this->nav = (int)$this->pageWithBackgrounds->fbiEnableNav;
        $this->navClick = (int)$this->pageWithBackgrounds->fbiNavClick;
        $this->navPrevNext = (int)$this->pageWithBackgrounds->fbiNavPrevNext;
        $this->centerX = (int)$this->pageWithBackgrounds->fbiCenterX;
        $this->centerY = (int)$this->pageWithBackgrounds->fbiCenterY;

        $this->compile($page, $request);
    }

    public function compile(PageModel $page, Request $request)
    {
        $images = [];
        $auxDate = [];

        $backgrounds = $this->backgroundFiles;

        while ($backgrounds->next()) {
            // Continue if the files has been processed or does not exist
            if (isset($images[$backgrounds->path]) || !file_exists(TL_ROOT . '/' . $backgrounds->path)) {
                continue;
            }

            // Single files
            if ($backgrounds->type === 'file') {
                $file = new File($backgrounds->path, true);

                if (!$file->isImage) {
                    continue;
                }

                $arrMeta = Frontend::getMetaData($backgrounds->meta, $page->language);

                if (empty($arrMeta)) {
                    if ($this->metaIgnore) {
                        continue;
                    }

                    if ($page->rootFallbackLanguage !== null) {
                        $arrMeta = Frontend::getMetaData($backgrounds->meta, $page->rootFallbackLanguage);
                    }
                }

                // Use the file name as title if none is given
                if ($arrMeta['title'] === '') {
                    $arrMeta['title'] = StringUtil::specialchars(str_replace('_', ' ', $file->filename));
                }

                // Add the image
                $images[$file->path] = [
                    'id' => $backgrounds->id,
                    'uuid' => $backgrounds->uuid,
                    'name' => $file->basename,
                    'singleSRC' => $file->path,
                    'alt' => ($arrMeta['caption'] ?: $arrMeta['title']),
                    'title' => $arrMeta['title'],
                    'imageUrl' => $arrMeta['link'],
                    'caption' => $arrMeta['caption'],
                ];

                $auxDate[] = $file->mtime;
            } else {
                $subfiles = FilesModel::findByPid($backgrounds->uuid);

                if ($subfiles === null) {
                    continue;
                }

                while ($subfiles->next()) {
                    // Skip subfolders
                    if ($subfiles->type === 'folder') {
                        continue;
                    }

                    $file = new File($subfiles->path, true);

                    if (!$file->isGdImage) {
                        continue;
                    }

                    $arrMeta = Frontend::getMetaData($subfiles->meta, $page->language);

                    // Use the file name as title if none is given
                    if ($arrMeta['title'] === '') {
                        $arrMeta['title'] = StringUtil::specialchars(str_replace('_', ' ', $file->filename));
                    }

                    // Add the image
                    $images[$file->path] = [
                        'id' => $subfiles->id,
                        'uuid' => $subfiles->uuid,
                        'name' => $file->basename,
                        'singleSRC' => $file->path,
                        'alt' => ($arrMeta['caption'] ?: $arrMeta['title']),
                        'title' => $arrMeta['title'],
                        'imageUrl' => $arrMeta['link'],
                        'caption' => $arrMeta['caption'],
                    ];

                    $auxDate[] = $file->mtime;
                }
            }

            // Sort array
            switch ($this->sortBy) {
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
                    if ($this->order !== '') {
                        $tmp = StringUtil::deserialize($this->order);

                        if (!empty($tmp) && is_array($tmp)) {
                            // Remove all values
                            $order = array_map(function () {
                            }, array_flip($tmp));

                            // Move the matching elements to their position in $arrOrder
                            foreach ($images as $k => $v) {
                                if (array_key_exists($v['uuid'], $order)) {
                                    $order[$v['uuid']] = $v;
                                    unset($images[$k]);
                                }
                            }

                            // Append the left-over images at the end
                            if (!empty($images)) {
                                $order = array_merge($order, array_values($images));
                            }

                            // Remove empty (unreplaced) entries
                            $images = array_values(array_filter($order));
                            unset($order);
                        }
                    }
                    break;

                case 'random':
                    shuffle($images);
                    break;
            }
        }

        // Limited images
        if ($this->fbiLimit && 0 !== $this->fbiLimit && count($images) > $this->fbiLimit) {
            $images = array_slice($images, 0, $this->fbiLimit);
        }

        $images = array_values($images);
        $imageIndex = 0;

        if (count($images)) {
            if ($this->mode === 'random') {
                mt_srand();
                $imageIndex = random_int(0, count($images) - 1);
            }

            if ($this->mode === 'single' || $this->mode === 'random') {
                $images = [$images[$imageIndex]];
            }

            $template = 'fbi_default';

            if ($this->fbiTemplate !== '' && $this->scopeMatcher->isFrontendRequest($request)) {
                $template = $this->fbiTemplate;
            }

            $templateObject = new FrontendTemplate($template);

            $templateObject->images = implode(
                ',',
                array_map(function ($image) {
                    $imgObj = new \stdClass();
                    $imgObj->src = $image['singleSRC'];
                    $imgObj->alt = $image['alt'];
                    $imgObj->title = $image['title'];
                    $imgObj->caption = $image['caption'];

                    return json_encode($imgObj);
                }, $images)
            );

            $templateObject->timeout = (int)$this->timeout ? $this->timeout : 12000;
            $templateObject->speed = (int)$this->speed ? $this->speed : 1200;
            $templateObject->caption = $this->caption ? 'true' : 'false';
            $templateObject->nav = $this->nav ? 'true' : 'false';
            $templateObject->navClick = $this->navClick ? 'true' : 'false';
            $templateObject->navPrevNext = $this->navPrevNext ? 'true' : 'false';
            $templateObject->centerX = $this->centerY ? 'true' : 'false';
            $templateObject->centerY = $this->centerY ? 'true' : 'false';

            // add javascript and css files
            $GLOBALS['TL_CSS'][] = 'bundles/oneupcontaofullbackgroundimages/css/style.css||static';
            $GLOBALS['TL_BODY'][] = $templateObject->parse();
            $GLOBALS['TL_BODY'][] = '<script type="text/javascript" src="bundles/oneupcontaofullbackgroundimages/js/eventListener.polyfill.js"></script>';
            $GLOBALS['TL_BODY'][] = '<script type="text/javascript" src="bundles/oneupcontaofullbackgroundimages/js/jquery.backstretch.min.js"></script>';
            $GLOBALS['TL_BODY'][] = '<script type="text/javascript" src="bundles/oneupcontaofullbackgroundimages/js/fullbackground.js"></script>';
        }
    }
}
