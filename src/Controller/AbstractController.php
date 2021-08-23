<?php

declare(strict_types=1);

namespace NoteApp\Controller;

use NoteApp\Request;
use NoteApp\View;
use NoteApp\Database;
use NoteApp\Exception\ConfigException;
use NoteApp\Exception\StorageException;
use NoteApp\Exception\NotFoundException;

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
    try {
      $action = $this->action() . 'Action';

      if(!method_exists($this,$action)) {
        $action = self::DEFAULT_ACTION . 'Action';
      }
      $this->$action();
    } catch (StorageException $e) {
      $this->view->render(
        'error',
        ['message' => $e->getMessage()]
      );
    } catch(NotFoundException $e) {
      $this->redirect('/',['error' => 'noteNotFound']);
    }
  }

  final protected function redirect(string $to, array $params): void
  {
    $location = $to;
    $queryParams = [];
    if(count($params)){
      foreach($params as $key => $value) {
        $queryParams[] = urlencode($key) . '=' . urlencode($value);
      }
      $queryParams = implode('&', $queryParams);
      $location .= '?' . $queryParams;
    }
    
    header("Location: $location");
    exit;
  }

  private function action(): string
  {
    return $this->request->getParam('action',self::DEFAULT_ACTION);
  }
}