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
    'mod_potential_avenger' => 'system/modules/potential-avenger/templates',
    'potential_avenger'     => 'system/modules/potential-avenger/templates',
));
