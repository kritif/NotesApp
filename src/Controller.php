<?php

declare(strict_types=1);

namespace NoteApp;

use NoteApp\Exception\ConfigException;

require_once("View.php");
require_once("Database.php");
require_once("src/Exception/ConfigException.php");

class Controller
{
  private const DEFAULT_ACTION = 'list';
  
  private static array $configuration = [];

  private array $request;
  private View $view;
  private Database $database;

  public static function initConfiguration(array $config):void
  {
    self::$configuration = $config;
  }

  public function __construct(array $request)
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
    $viewParams = [];

    switch ($this->action()) {
      case 'create':
        $page = 'create';
        $data = $this->getRequestPost();
        if (!empty($data)) {         
          $this->database->createNote(
            [
              'title' => $data['title'],
              'description' => $data['description']
            ]
          );
          header('Location: /?before=created');
        }
        break;
      case 'show':
        $viewParams = [
          'title' => 'Title',
          'description' => 'Description'
        ];
        break;
      default:
        $page = 'list';

        $data = $this->getRequestGet();
        $this->database->getNoteList();
        $viewParams['before'] = $data['before'] ?? null;
        break;
    };

    $this->view->render($page, $viewParams);
  }

  private function getRequestPost(): array
  {
    return $this->request['post'] ?? [];
  }

  private function getRequestGet(): array
  {
    return $this->request['get'] ?? [];
  }

  private function action(): string
  {
    $data = $this->getRequestGet();
    return $data['action'] ?? self::DEFAULT_ACTION;
  }
}
