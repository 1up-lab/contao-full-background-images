<?php

ClassLoader::addNamespaces(array
(
    'Oneup',
));

ClassLoader::addClasses(array
(
    'Oneup\PotentialAvenger'        => 'system/modules/potential-avenger/classes/PotentialAvenger.php',
));

TemplateLoader::addFiles(array
(
    'potential_avenger'     => 'system/modules/potential-avenger/templates',
));
