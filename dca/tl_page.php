<?php

\Controller::loadLanguageFile('tl_content');

$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'pam';
$GLOBALS['TL_DCA']['tl_page']['palettes']['regular']        = str_replace('includeChmod;', 'includeChmod;{pa_legend:hide},pam;',$GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['root']           = str_replace('includeChmod;', 'includeChmod;{pa_legend:hide},pam;',$GLOBALS['TL_DCA']['tl_page']['palettes']['root']);


$GLOBALS['TL_DCA']['tl_page']['subpalettes']['pam_inherit'] = '';
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['pam_disable'] = '';
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['pam_choose']  = 'paSRC,sortBy,overwriteModule,paImgMode,paTimeout,paSpeed';

$GLOBALS['TL_DCA']['tl_page']['fields']['pam'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['pam'],
    'default'                 => 'inherit',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('inherit', 'choose', 'disable'),
    'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true),
    'reference'               => &$GLOBALS['TL_LANG']['tl_page'],
    'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['overwriteModule'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['overwriteModule'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
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
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['sortBy'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('custom', 'name_asc', 'name_desc', 'date_asc', 'date_desc', 'random'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
    'eval'                    => array('tl_class'=>''),
    'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['paImgMode'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['paImgMode'],
    'default'                 => 'paMultiple',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('', 'paSingle', 'paSingleRandom', 'paMultiple'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_page'],
    'eval'                    => array('helpwizard'=>true, 'chosen'=>true),
    'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['paTimeout'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['paTimeout'],
    'default'                 => 12000,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
    'sql'                     => "varchar(10) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['paSpeed'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['paSpeed'],
    'default'                 => 1000,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
    'sql'                     => "varchar(10) NOT NULL default ''"
);
