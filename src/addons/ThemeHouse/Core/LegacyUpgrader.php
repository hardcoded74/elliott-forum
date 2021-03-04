<?php
$curDir = dirname(__FILE__);

$version = 0;
if ($handle = opendir($curDir . '/LegacyUpgrader')) {
    while (($entry = readdir($handle)) !== false) {
        if (intval($entry) > $version) {
            $version = intval($entry);
        }
    }
}

require_once $curDir . '/LegacyUpgrader/' . $version . '.php';