<?php

$GLOBALS['FBI']['helperClass'] = 'Oneup\Contao\Fbi\Helper\FbiHelper';

$GLOBALS['TL_HOOKS']['generatePage'][] = array('Oneup\Contao\Fbi\Runner', 'generate');
