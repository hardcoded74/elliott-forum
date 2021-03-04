<?php
ini_set('display_errors', true);
$config['db']['host'] = 'h42-rds1.cianhrgcrwbe.us-east-1.rds.amazonaws.com';
$config['db']['port'] = '3306';
$config['db']['username'] = 'elliott_xen';
$config['db']['password'] = 'tw9waujkwa46';
$config['db']['dbname'] = 'elliott_xen';
$config['superAdmins'] = '1,14, 122, 430, 638';
$config['enableTfa'] = false;
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) { $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP']; }
