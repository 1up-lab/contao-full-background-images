<?php

$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'usePotentialAvenger';

$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace
(
    'noSearch;',
    'noSearch;{pa_legend:hide},usePotentialAvenger;',
    $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']
);

$GLOBALS['TL_DCA']['tl_page']['subpalettes']['usePotentialAvenger'] = 'paSRC,sortBy';

$GLOBALS['TL_DCA']['tl_page']['fields']['usePotentialAvenger'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['usePotentialAvenger'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['paSRC'] = array
(

    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['paSRC'],
    'exclude'                 => true,
    'inputType'               => 'fileTree',
    'eval'                    => array('multiple'=>true, 'fieldType'=>'checkbox', 'orderField'=>'paOrder', 'files'=>true, 'mandatory'=>true),
    'sql'                     => "blob NULL",
);

$GLOBALS['TL_DCA']['tl_page']['fields']['paOrder'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['paOrder'],
    'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['sortBy'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['sortBy'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('custom', 'name_asc', 'name_desc', 'date_asc', 'date_desc', 'random'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_page'],
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(32) NOT NULL default ''"
);
