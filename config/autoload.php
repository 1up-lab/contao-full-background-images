<?php

ClassLoader::addNamespaces(array
(
    'Oneup',
));

ClassLoader::addClasses(array
(
    'Oneup\ModulePotentialAvenger'                      => 'system/modules/potential-avenger/modules/ModulePotentialAvenger.php',
));

TemplateLoader::addFiles(array
(
    'potential_avenger'    => 'system/modules/potential-avenger/templates',
));
