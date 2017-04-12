<?php

\Controller::loadLanguageFile('tl_content');

$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'] = array_merge(
    $GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'],
    [
        'fbi',
    ]
);

$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = str_replace(
    'includeChmod;',
    'includeChmod;{fbi_legend:hide},fbi;',
    $GLOBALS['TL_DCA']['tl_page']['palettes']['root']
);

$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace(
    'includeChmod;',
    'includeChmod;{fbi_legend:hide},fbi;',
    $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']
);

$GLOBALS['TL_DCA']['tl_page']['palettes']['error_403'] = str_replace(
    'includeChmod;',
    'includeChmod;{fbi_legend:hide},fbi;',
    $GLOBALS['TL_DCA']['tl_page']['palettes']['error_403']
);

$GLOBALS['TL_DCA']['tl_page']['palettes']['error_404'] = str_replace(
    'includeChmod;',
    'includeChmod;{fbi_legend:hide},fbi;',
    $GLOBALS['TL_DCA']['tl_page']['palettes']['error_404']
);

$GLOBALS['TL_DCA']['tl_page']['subpalettes'] = array_merge(
    $GLOBALS['TL_DCA']['tl_page']['subpalettes'],
    [
        'fbi_inherit'   => '',
        'fbi_disable'   => '',
        'fbi_choose'    => 'fbiSRC,sortBy,fbiLimit,fbiImgCaption,fbiImgMode,fbiTimeout,fbiSpeed,fbiEnableNav,fbiNavClick,fbiNavPrevNext,fbiCenterX,fbiCenterY,fbiTemplate;',
    ]
);

$GLOBALS['TL_DCA']['tl_page']['fields'] = array_merge(
    $GLOBALS['TL_DCA']['tl_page']['fields'],
    [
        'fbi' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbi'],
            'default'   => 'inherit',
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => [
                'inherit',
                'choose',
                'disable',
            ],
            'eval'      => [
                'helpwizard'        => true,
                'submitOnChange'    => true,
                'tl_class'      => 'clr',
            ],
            'reference' => &$GLOBALS['TL_LANG']['tl_page'],
            'sql'       => "varchar(32) NOT NULL default ''",
        ],
        'fbiSRC' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbiSRC'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => [
                'multiple'      => true,
                'fieldType'     => 'checkbox',
                'orderField'    => 'fbiOrder',
                'files'         => true,
                'mandatory'     => true,
                'isGallery'     => true,
            ],
            'sql'       => "blob NULL",
        ],
        'fbiOrder' => [
            'label' => &$GLOBALS['TL_LANG']['tl_page']['fbiOrder'],
            'sql'   => "blob NULL",
        ],
        'sortBy' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_content']['sortBy'],
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => [
                'custom',
                'name_asc',
                'name_desc',
                'date_asc',
                'date_desc',
                'random',
            ],
            'reference' => &$GLOBALS['TL_LANG']['tl_content'],
            'eval'      => [
                'tl_class' => 'w50',
            ],
            'sql'       => "varchar(32) NOT NULL default ''",
        ],
        'fbiLimit' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbiLimit'],
            'default'   => 0,
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => [
                'rgxp'      => 'digit',
                'tl_class'  => 'w50',
            ],
            'sql'       => "varchar(10) NOT NULL default '0'",
        ],
        'fbiImgCaption' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbiImgCaption'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => [
                'tl_class' => 'w50',
            ],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'fbiImgMode' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbiImgMode'],
            'default'   => 'paMultiple',
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => [
                'single',
                'random',
                'multiple',
            ],
            'reference' => &$GLOBALS['TL_LANG']['tl_page'],
            'eval'      => [
                'helpwizard'    => true,
                'chosen'        => true,
                'tl_class'      => 'w50',
            ],
            'sql'       => "varchar(32) NOT NULL default ''",
        ],
        'fbiTimeout' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbiTimeout'],
            'default'   => 12000,
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => [
                'rgxp'      => 'digit',
                'tl_class'  => 'w50',
                'mandatory' => true,
            ],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'fbiSpeed' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbiSpeed'],
            'default'   => 1000,
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => [
                'rgxp'      => 'digit',
                'tl_class'  => 'w50',
                'mandatory' => true,
            ],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'fbiEnableNav' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbiEnableNav'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => [
                'tl_class' => 'w50',
            ],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'fbiNavClick' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbiNavClick'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => [
                'tl_class' => 'w50',
            ],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'fbiNavPrevNext' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbiNavPrevNext'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => [
                'tl_class' => 'clr m12',
            ],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'fbiCenterX' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbiCenterX'],
            'exclude'   => true,
            'default'   => 1,
            'inputType' => 'checkbox',
            'eval'      => [
                'tl_class' => 'w50',
            ],
            'sql'       => "char(1) NOT NULL default '1'",
        ],
        'fbiCenterY' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['fbiCenterY'],
            'exclude'   => true,
            'default'   => 1,
            'inputType' => 'checkbox',
            'eval'      => [
                'tl_class' => 'w50',
            ],
            'sql'       => "char(1) NOT NULL default '1'",
        ],
        'fbiTemplate' => [
            'label'             => &$GLOBALS['TL_LANG']['tl_page']['fbiTemplate'],
            'exclude'           => true,
            'inputType'         => 'select',
            'options_callback'  => [
                'Oneup\Contao\Fbi\Helper\Dca\DcaHelper',
                'getElementTemplates',
            ],
            'eval'              => [
                'includeBlankOption'    => true,
                'chosen'                => true,
                'tl_class'              => 'w50',
            ],
            'sql'               => "varchar(64) NOT NULL default ''",
        ],
    ]
);
