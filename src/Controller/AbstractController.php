<?php

declare(strict_types=1);

namespace NoteApp\Controller;

require_once("src/View.php");
require_once("src/Database.php");
require_once("src/Exception/ConfigException.php");
require_once("src/Request.php");

use NoteApp\Exception\ConfigException;
use NoteApp\Request;
use NoteApp\View;
use NoteApp\Database;


abstract class AbstractController 
{
  protected const DEFAULT_ACTION = 'list';
  
  private static array $configuration = [];

  protected Request $request;
  protected View $view;
  protected Database $database;

  public static function initConfiguration(array $config):void
  {
    self::$configuration = $config;
  }

  public function __construct(Request $request)
  { 
    if(empty(self::$configuration['db'])) {
      throw new ConfigException('Wrong configuration data');
    }
    $this->database = new Database(self::$configuration['db']);
    $this->request = $request;
    $this->view = new View();
  }

  public function run(): void
  { 
    $action = $this->action() . 'Action';

    if(!method_exists($this,$action)) {
      $action = self::DEFAULT_ACTION . 'Action';
    }
    $this->$action(); 
  }

  private function action(): string
  {
    return $this->request->getParam('action',self::DEFAULT_ACTION);
  }
}