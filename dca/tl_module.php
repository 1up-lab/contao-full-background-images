<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'paMode';

$GLOBALS['TL_DCA']['tl_module']['palettes']['potentialAvenger'] = "{title_legend},name,type;{paMode_legend},paMode;{paSettings_legend},paTimeout,paSpeed";

$GLOBALS['TL_DCA']['tl_module']['fields']['paMode'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['paMode'],
    'default'                 => 'paMultiple',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('paMultiple', 'paSingle', 'paSingleRandom'),
    'reference'               => &$GLOBALS['TL_LANG']['FE_MOD'],
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
