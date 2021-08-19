<?php

declare(strict_types=1);

namespace NoteApp;

require_once('src/Exception/AppException.php');
require_once('src/Exception/ConfigException.php');
require_once("src/Utils/debug.php");
require_once("src/Controller.php");

use NoteApp\Exception\AppException;
use NoteApp\Exception\ConfigException;
use Throwable;

$configuration = require_once("config/config.php");

$request = [
  'get' => $_GET,
  'post' => $_POST
];

try {
  Controller::initConfiguration($configuration);
  (new Controller($request))->run();
} catch(ConfigException $e) {
  echo 'Configuration error. Please contact to admin@server.com';
} catch(AppException $e) {
  echo 'Application error: ';
  echo $e->getMessage();
} catch(Throwable $e) {
  echo "Application error";
}

