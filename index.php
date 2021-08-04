<?php

declare(strict_types=1);

namespace NoteApp;

require_once("src/Utils/debug.php");
require_once("src/Controller.php");

$request = [
  'get' => $_GET,
  'post' => $_POST
];

(new Controller($request))->run();