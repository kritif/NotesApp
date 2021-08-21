<?php

declare(strict_types=1);

namespace NoteApp;

spl_autoload_register(function (string $name) {
  $path = str_replace('\\','/', $name);
  $path = str_replace('NoteApp','src', $name);
  require_once($path . '.php');
});

use NoteApp\Controller\NoteController;
use NoteApp\Controller\AbstractController;
use NoteApp\Exception\AppException;
use NoteApp\Exception\ConfigException;
use Throwable;

$configuration = require_once("config/config.php");

$request = new Request($_GET, $_POST, $_SERVER);

try {
  AbstractController::initConfiguration($configuration);
  (new NoteController($request))->run();
} catch(ConfigException $e) {
  echo 'Configuration error. Please contact to admin@server.com';
} catch(AppException $e) {
  echo 'Application error: ';
  echo $e->getMessage();
} catch(Throwable $e) {
  echo "Application error";
  echo $e->getMessage();
}

