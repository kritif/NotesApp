<?php

declare(strict_types=1);

namespace NoteApp;

require_once("src/Utils/debug.php");
require_once("src/View.php");

// error_reporting(0); 
// ini_set('display_errors','0');

const DEFAULT_ACTION = 'list';

$view = new View();

$viewParams = [];
$action = $_GET['action'] ?? DEFAULT_ACTION;

if ($action === 'create') {
  $page = 'create';
  $viewParams['resultCreate'] = 'create';
} else {
  $page = 'list';
  $viewParams['resultList'] = 'list';
}

$view->render($page, $viewParams);
