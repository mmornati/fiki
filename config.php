<?php
$CONFIGURATION_FILE='./configuration.ini';

if (!$settings = parse_ini_file($CONFIGURATION_FILE, TRUE))
        throw new exception('Unable to open ' . $CONFIGURATION_FILE . '.');

?>
