<?php
$curDir = dirname(__FILE__);

$version = 0;
if ($handle = opendir($curDir . '/UpdateCheck')) {
    while (($entry = readdir($handle)) !== false) {
        if (intval($entry) > $version) {
            $version = intval($entry);
        }
    }
}

require_once $curDir . '/UpdateCheck/' . $version . '.php';