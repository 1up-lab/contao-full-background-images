<?php

ClassLoader::addNamespaces(array
(
    'Oneup',
));

ClassLoader::addClasses(array
(
    'Oneup\ContaoFullBgImage'  => 'system/modules/contao-full-background-image/classes/ContaoFullBgImage.php',
));

TemplateLoader::addFiles(array
(
    'oneup_ct_fullbgimage'     => 'system/modules/contao-full-background-image/templates',
));
