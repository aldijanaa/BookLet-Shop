<?php
// Report all errors accept E_NOTICE
ini_set('display_errors', 1);   //All errors should be displayed
ini_set('display_startup_errors', 1);  //startup errors also displayed
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));     //All errors shown except E_NOTICE and E_DEPRECATED

// Database access credentials
define('DB_NAME', 'web_projekat_booklet');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASS', 'aldijanaaldijana5');
define('DB_HOST', '127.0.0.1');

define('JWT_SECRET_KEY', '1kjlh4kjl132h4kj132h4lkj2h4lkj12h4klj132h4kjh123l4kjh2');
