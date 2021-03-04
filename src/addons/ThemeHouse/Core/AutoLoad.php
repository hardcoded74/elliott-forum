<?php
$curDir = dirname(__FILE__);

$version = 0;
if ($handle = opendir($curDir . '/AutoLoad')) {
    while (($entry = readdir($handle)) !== false) {
        if (intval($entry) > $version) {
            $version = intval($entry);
        }
    }
}

require_once $curDir . '/AutoLoad/' . $version . '.php';