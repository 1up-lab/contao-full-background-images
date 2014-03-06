<?php

\Controller::loadLanguageFile('tl_content');

$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][]  = array('PotentialAvengerModule', 'showJsLibraryHint');
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][]   = 'paImgMode';
$GLOBALS['TL_DCA']['tl_module']['palettes']['potentialAvenger'] = "{title_legend},name,type;{paSettings_legend},paImgMode,paTimeout,paSpeed";

$GLOBALS['TL_DCA']['tl_module']['fields']['paImgMode'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['paImgMode'],
    'default'                 => 'paMultiple',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('paSingle', 'paSingleRandom', 'paMultiple'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('helpwizard'=>true, 'chosen'=>true),
    'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['paTimeout'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['paTimeout'],
    'default'                 => 12000,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>true),
    'sql'                     => "varchar(10) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['paSpeed'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['paSpeed'],
    'default'                 => 1000,
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>true),
    'sql'                     => "varchar(10) NOT NULL default ''"
);

class PotentialAvengerModule extends \Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function showJsLibraryHint($dc)
    {
        if ($_POST || \Input::get('act') != 'edit')
        {
            return;
        }

        // Return if the user cannot access the layout module
        if (!$this->User->hasAccess('themes', 'modules') || !$this->User->hasAccess('layout', 'themes'))
        {
            return;
        }

        $objMod = \ModuleModel::findByPk($dc->id);

        if ($objMod === null)
        {
            return;
        }

        switch ($objMod->type)
        {
            case 'potentialAvenger':
                \Message::addInfo($GLOBALS['TL_LANG']['tl_module']['includePotentialAvenger']);
                break;
        }
    }
}
