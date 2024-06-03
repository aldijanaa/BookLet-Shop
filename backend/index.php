<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

/*Testing purpose
Flight::route ('GET /', function() {
    return Flight::json('Hello World!');
});*/

require_once __DIR__ . '/rest/routes/MiddlewareRoutes.php';
require_once __DIR__ . '/rest/routes/BookRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/CartRoutes.php';
require_once __DIR__ . '/rest/routes/FavoritesRoutes.php';



Flight::start();
