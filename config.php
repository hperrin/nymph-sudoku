<?php
// Nymph's configuration.

$nymph_config = include(dirname(__FILE__).DIRECTORY_SEPARATOR.'bower_components/nymph/conf/defaults.php');

$nymph_config->MySQL->database['value'] = 'sudoku';
$nymph_config->MySQL->user['value'] = 'sudoku';
$nymph_config->MySQL->password['value'] = 'hunterisawesome';

return $nymph_config;