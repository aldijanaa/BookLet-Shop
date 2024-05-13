<?php

require 'vendor/autoload.php';
require 'config.php';

require './rest/routes/UserRoutes.php';
require './rest/routes/AuthRoutes.php';
require './rest/routes/BookRoutes.php';
require './rest/routes/FavoritesRoutes.php';



Flight::start();
