<?php

\Controller::loadLanguageFile('tl_content');

$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'usePotentialAvenger';

$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace
(
    'includeChmod;',
    'noSincludeChmodearch;{pa_legend:hide},usePotentialAvenger;',
    $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']
);

$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = str_replace
(
    'includeChmod;',
    'includeChmod;{pa_legend:hide},usePotentialAvenger;',
    $GLOBALS['TL_DCA']['tl_page']['palettes']['root']
);


$GLOBALS['TL_DCA']['tl_page']['subpalettes']['usePotentialAvenger'] = 'paSRC,sortBy,overwriteModule,paMode,paTimeout,paSpeed;';

$GLOBALS['TL_DCA']['tl_page']['fields']['usePotentialAvenger'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['usePotentialAvenger'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['overwriteModule'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['overwriteModule'],
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
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['sortBy'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('custom', 'name_asc', 'name_desc', 'date_asc', 'date_desc', 'random'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
    'eval'                    => array('tl_class'=>''),
    'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['paMode'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['paMode'],
    'default'                 => 'paMultiple',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('paSingle', 'paSingleRandom', 'paMultiple'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_page'],
    'eval'                    => array('helpwizard'=>true, 'chosen'=>true, 'submitOnChange'=>true),
    'sql'                     => "varchar(32) NOT NULL default 'paSingle'"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['paTimeout'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['paTimeout'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
    'sql'                     => "varchar(10) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['paSpeed'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['paSpeed'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
    'sql'                     => "varchar(10) NOT NULL default ''"
);
