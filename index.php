<?php

declare(strict_types=1);

namespace NoteApp;

$configuration = require_once("config/config.php");
require_once("src/Utils/debug.php");
require_once("src/Controller.php");

$request = [
  'get' => $_GET,
  'post' => $_POST
];

Controller::initConfiguration($configuration);

(new Controller($request))->run();