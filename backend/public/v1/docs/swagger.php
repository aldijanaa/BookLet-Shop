<?php

require __DIR__ . '/../../../vendor/autoload.php';

define('BASE_URL', 'http://localhost/WEB_Projekat%20sa%20spappom/backend/');     //base url for the project

error_reporting(0);

//Calling scan method to scan any methods coming from rest or / folder
$openapi = \OpenApi\Generator::scan(['../../../rest', './'], ['pattern' => '*.php']);
// $openapi = \OpenApi\Util::finder(['../../../rest/routes', './'], NULL, '*.php');
// $openapi = \OpenApi\scan(['../../../rest', './'], ['pattern' => '*.php']);

//$openapi = \OpenApi\Generator::scan(['../../../rest', './']);
header('Content-Type: application/x-yaml'); // content shown in yaml format
echo $openapi->toYaml();
