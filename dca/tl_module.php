<?php

\Controller::loadLanguageFile('tl_content');

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'paMode';

$GLOBALS['TL_DCA']['tl_module']['palettes']['potentialAvenger'] = "{title_legend},name,type;{paSettings_legend},paMode,paTimeout,paSpeed";

$GLOBALS['TL_DCA']['tl_module']['fields']['paMode'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['paMode'],
    'default'                 => 'paMultiple',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('paSingle', 'paSingleRandom', 'paMultiple'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('helpwizard'=>true, 'chosen'=>true, 'submitOnChange'=>true),
    'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['paTimeout'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['paTimeout'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '12000'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['paSpeed'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['paSpeed'],
    'default'                 => 0,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '1000'"
);
