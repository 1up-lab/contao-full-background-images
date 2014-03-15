<?php

ClassLoader::addNamespaces(array
(
    'Oneup',
));

ClassLoader::addClasses(array
(
    'Oneup\ContaoFullBgImage'  => 'system/modules/full-background-images/classes/ContaoFullBgImage.php',
));

TemplateLoader::addFiles(array
(
    'oneup_ct_fullbgimage'     => 'system/modules/full-background-images/templates',
));
