<?php
require 'vendor/autoload.php';
require 'config.php';


require './rest/routes/AuthRoutes.php';
require './rest/routes/BookRoutes.php';
require './rest/routes/CartRoutes.php';
require './rest/routes/FavoritesRoutes.php';
require './rest/routes/UserRoutes.php';


//novo za middleware
require './rest/routes/MiddlewareRoutes.php';





Flight::start();
